<?php

namespace App\Http\Controllers\Caregiver;

use App\Enums\BookingStatus;
use App\Enums\DocumentType;
use App\Enums\EarningStatus;
use App\Enums\HiringRequestStatus;
use App\Enums\PayoutStatus;
use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CaregiverAvailability;
use App\Models\CaregiverBlockedDate;
use App\Models\CaregiverEarning;
use App\Models\HiringRequest;
use App\Models\Location;
use App\Models\Payout;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Services\HiringWorkflowService;
use App\Services\PrivacyFilterService;
use App\Services\SecureDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CaregiverDashboardController extends Controller
{
    public function __construct(
        public HiringWorkflowService $workflowService,
        public PrivacyFilterService $privacyService,
        public SecureDocumentService $documentService
    ) {}

    public function index(): View
    {
        $caregiver = Auth::user()->caregiver;

        // Calculate profile completion percentage
        $completionFields = [
            'about', 'bio', 'education_qualification', 'daily_rate',
            'years_experience', 'city', 'nid_number',
        ];
        $filled = 0;
        foreach ($completionFields as $f) {
            if (! empty($caregiver->{$f})) {
                $filled++;
            }
        }
        if ($caregiver->documents()->exists()) {
            $filled++;
        }
        if ($caregiver->services()->exists()) {
            $filled++;
        }
        $profileCompletion = min(100, (int) round(($filled / (count($completionFields) + 2)) * 100));

        $pendingRequestsCount = HiringRequest::where('caregiver_id', $caregiver->id)
            ->where('status', HiringRequestStatus::CaregiverNotified)
            ->count();

        $activeJobsCount = Booking::where('caregiver_id', $caregiver->id)
            ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::InProgress])
            ->count();

        $completedJobsCount = Booking::where('caregiver_id', $caregiver->id)
            ->where('status', BookingStatus::Completed)
            ->count();

        $totalEarnings = CaregiverEarning::where('caregiver_id', $caregiver->id)
            ->whereIn('status', [EarningStatus::Available, EarningStatus::Pending])
            ->sum('net_amount');

        $availableEarnings = CaregiverEarning::where('caregiver_id', $caregiver->id)
            ->where('status', EarningStatus::Available)
            ->sum('net_amount');

        $withdrawn = Payout::where('caregiver_id', $caregiver->id)
            ->whereIn('status', PayoutStatus::reserving())
            ->orWhere(fn ($q) => $q->where('caregiver_id', $caregiver->id)->where('status', PayoutStatus::Paid))
            ->sum('amount');

        $availableBalance = max(0, $availableEarnings - $withdrawn);

        $pendingEarnings = CaregiverEarning::where('caregiver_id', $caregiver->id)
            ->where('status', EarningStatus::Pending)
            ->sum('net_amount');

        $recentRequests = HiringRequest::where('caregiver_id', $caregiver->id)
            ->whereIn('status', HiringRequestStatus::visibleToCaregiver())
            ->with(['service', 'area', 'district'])
            ->latest()
            ->take(5)
            ->get();

        $recentJobs = Booking::where('caregiver_id', $caregiver->id)
            ->with(['service', 'area', 'district'])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'pending_requests' => $pendingRequestsCount,
            'active_jobs' => $activeJobsCount,
            'completed_jobs' => $completedJobsCount,
            'total_earnings' => $totalEarnings,
            'available_balance' => $availableBalance,
            'pending_earnings' => $pendingEarnings,
            'average_rating' => $caregiver->rating_avg ?? 5.0,
        ];

        $assignedRequests = $recentRequests;
        $upcomingJobs = $recentJobs;

        return view('caregiver.dashboard', compact(
            'caregiver',
            'profileCompletion',
            'stats',
            'pendingRequestsCount',
            'activeJobsCount',
            'completedJobsCount',
            'totalEarnings',
            'availableBalance',
            'pendingEarnings',
            'recentRequests',
            'recentJobs',
            'assignedRequests',
            'upcomingJobs'
        ));
    }

    public function requests(): View
    {
        $caregiver = Auth::user()->caregiver;

        $requests = HiringRequest::where('caregiver_id', $caregiver->id)
            ->whereIn('status', HiringRequestStatus::visibleToCaregiver())
            ->with(['service', 'area', 'district'])
            ->latest()
            ->paginate(10);

        return view('caregiver.requests.index', compact('requests'));
    }

    public function showRequest(HiringRequest $request): View
    {
        Gate::authorize('view', $request);

        $request->load(['service', 'area', 'district', 'division', 'statusLogs']);
        $brief = $this->privacyService->caregiverRequestBrief($request);

        return view('caregiver.requests.show', compact('request', 'brief'));
    }

    public function acceptRequest(Request $request, HiringRequest $hiringRequest): RedirectResponse
    {
        Gate::authorize('respond', $hiringRequest);

        $caregiver = Auth::user()->caregiver;
        $note = $request->input('note');

        $this->workflowService->caregiverAccept($hiringRequest, $caregiver, $note);

        return redirect()->route('caregiver.requests.show', $hiringRequest)
            ->with('success', 'You have accepted the care request! CareMate Admin will finalize booking confirmation.');
    }

    public function declineRequest(Request $request, HiringRequest $hiringRequest): RedirectResponse
    {
        Gate::authorize('respond', $hiringRequest);

        $caregiver = Auth::user()->caregiver;
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $this->workflowService->caregiverDecline($hiringRequest, $caregiver, $validated['reason']);

        return redirect()->route('caregiver.requests.index')
            ->with('info', 'You declined the request. CareMate Admin has been notified to reassign the client.');
    }

    public function jobs(): View
    {
        $caregiver = Auth::user()->caregiver;

        $jobs = Booking::where('caregiver_id', $caregiver->id)
            ->with(['service', 'area', 'district', 'earning'])
            ->latest()
            ->paginate(10);

        return view('caregiver.jobs.index', compact('jobs'));
    }

    public function showJob(Booking $booking): View
    {
        Gate::authorize('view', $booking);

        $booking->load(['service', 'area', 'district', 'earning', 'statusLogs', 'hiringRequest']);

        return view('caregiver.jobs.show', compact('booking'));
    }

    public function availability(): View
    {
        $caregiver = Auth::user()->caregiver;
        $availabilities = $caregiver->availabilities->keyBy('day_of_week');
        $blockedDates = $caregiver->blockedDates()->where('date', '>=', now()->toDateString())->orderBy('date')->get();

        return view('caregiver.availability', compact('caregiver', 'availabilities', 'blockedDates'));
    }

    public function updateAvailability(Request $request): RedirectResponse
    {
        $caregiver = Auth::user()->caregiver;

        $validated = $request->validate([
            'days' => ['array'],
            'days.*' => ['integer', 'between:0,6'],
            'start_time' => ['array'],
            'end_time' => ['array'],
            'is_available' => ['boolean'],
        ]);

        DB::transaction(function () use ($caregiver, $validated, $request): void {
            $caregiver->update([
                'is_available' => $request->boolean('is_available'),
            ]);

            $caregiver->availabilities()->delete();

            $selectedDays = $validated['days'] ?? [];
            foreach ($selectedDays as $day) {
                CaregiverAvailability::create([
                    'caregiver_id' => $caregiver->id,
                    'day_of_week' => (int) $day,
                    'start_time' => $validated['start_time'][$day] ?? '08:00',
                    'end_time' => $validated['end_time'][$day] ?? '18:00',
                    'is_full_day' => false,
                ]);
            }
        });

        return back()->with('success', 'Your working availability has been updated.');
    }

    public function blockDate(Request $request): RedirectResponse
    {
        $caregiver = Auth::user()->caregiver;

        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'reason' => ['nullable', 'string', 'max:100'],
        ]);

        CaregiverBlockedDate::updateOrCreate(
            ['caregiver_id' => $caregiver->id, 'date' => $validated['date']],
            ['reason' => $validated['reason'] ?? 'Unavailable']
        );

        return back()->with('success', 'Date blocked successfully.');
    }

    public function unblockDate(CaregiverBlockedDate $blockedDate): RedirectResponse
    {
        $caregiver = Auth::user()->caregiver;
        abort_unless($blockedDate->caregiver_id === $caregiver->id, 403);

        $blockedDate->delete();

        return back()->with('success', 'Blocked date removed.');
    }

    public function earnings(): View
    {
        $caregiver = Auth::user()->caregiver;

        $totalEarnings = CaregiverEarning::where('caregiver_id', $caregiver->id)->sum('net_amount');
        $availableEarnings = CaregiverEarning::where('caregiver_id', $caregiver->id)->where('status', EarningStatus::Available)->sum('net_amount');
        $withdrawn = Payout::where('caregiver_id', $caregiver->id)->whereIn('status', [PayoutStatus::Requested, PayoutStatus::Approved, PayoutStatus::Paid])->sum('amount');
        $availableBalance = max(0, $availableEarnings - $withdrawn);
        $pendingEarnings = CaregiverEarning::where('caregiver_id', $caregiver->id)->where('status', EarningStatus::Pending)->sum('net_amount');

        $earnings = CaregiverEarning::where('caregiver_id', $caregiver->id)
            ->with('booking.service')
            ->latest()
            ->paginate(10);

        $payouts = Payout::where('caregiver_id', $caregiver->id)->latest()->get();
        $minPayout = (float) Setting::get('min_payout_amount', 1000);

        $balance = [
            'available' => $availableBalance,
            'pending' => $pendingEarnings,
            'total_paid' => Payout::where('caregiver_id', $caregiver->id)->where('status', PayoutStatus::Paid)->sum('amount'),
        ];

        return view('caregiver.earnings', compact(
            'caregiver',
            'balance',
            'totalEarnings',
            'availableBalance',
            'pendingEarnings',
            'earnings',
            'payouts',
            'minPayout'
        ));
    }

    public function storePayout(Request $request): RedirectResponse
    {
        $caregiver = Auth::user()->caregiver;
        $minPayout = (float) Setting::get('min_payout_amount', 1000);

        $availableEarnings = CaregiverEarning::where('caregiver_id', $caregiver->id)->where('status', EarningStatus::Available)->sum('net_amount');
        $withdrawn = Payout::where('caregiver_id', $caregiver->id)->whereIn('status', [PayoutStatus::Requested, PayoutStatus::Approved, PayoutStatus::Paid])->sum('amount');
        $availableBalance = max(0, $availableEarnings - $withdrawn);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:'.$minPayout, 'max:'.$availableBalance],
            'method' => ['required', 'in:bkash,nagad,rocket,bank_transfer'],
            'account_details' => ['required', 'string', 'max:255'],
        ]);

        Payout::create([
            'caregiver_id' => $caregiver->id,
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'account_details' => $validated['account_details'],
            'status' => PayoutStatus::Requested,
        ]);

        return back()->with('success', 'Payout request of ৳'.number_format($validated['amount'], 2).' submitted to CareMate Accounts.');
    }

    public function documents(): View
    {
        $caregiver = Auth::user()->caregiver;
        $documents = $caregiver->documents()->latest()->get();
        $certificates = $caregiver->certificates()->latest()->get();

        return view('caregiver.documents', compact('caregiver', 'documents', 'certificates'));
    }

    public function storeDocument(Request $request): RedirectResponse
    {
        $caregiver = Auth::user()->caregiver;

        $validated = $request->validate([
            'type' => ['required', 'string'],
            'title' => ['required', 'string', 'max:120'],
            'file' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
        ]);

        $docType = DocumentType::tryFrom($validated['type']) ?? DocumentType::Other;

        $this->documentService->storePrivateDocument(
            $caregiver,
            $request->file('file'),
            $docType,
            $validated['title']
        );

        return back()->with('success', 'Document uploaded securely. CareMate will verify it.');
    }

    public function support(): View
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->with(['messages', 'booking'])
            ->latest()
            ->paginate(10);

        $caregiverJobs = Booking::where('caregiver_id', Auth::user()->caregiver->id)->latest()->get();

        return view('caregiver.support.index', compact('tickets', 'caregiverJobs'));
    }

    public function storeTicket(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'booking_id' => ['nullable', 'exists:bookings,id'],
        ]);

        $ticket = DB::transaction(function () use ($validated): SupportTicket {
            $ticket = SupportTicket::create([
                'user_id' => Auth::id(),
                'booking_id' => $validated['booking_id'] ?? null,
                'category' => TicketCategory::tryFrom($validated['category']) ?? TicketCategory::General,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'priority' => TicketPriority::tryFrom($validated['priority']) ?? TicketPriority::Normal,
                'status' => TicketStatus::Open,
                'last_reply_at' => now(),
            ]);

            SupportMessage::create([
                'support_ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'message' => $validated['message'],
            ]);

            return $ticket;
        });

        return redirect()->route('caregiver.support.show', $ticket)
            ->with('success', "Support ticket #{$ticket->ticket_number} created.");
    }

    public function showTicket(SupportTicket $ticket): View
    {
        Gate::authorize('view', $ticket);
        $ticket->load(['messages.user', 'booking.service']);

        return view('caregiver.support.show', compact('ticket'));
    }

    public function replyTicket(Request $request, SupportTicket $ticket): RedirectResponse
    {
        Gate::authorize('reply', $ticket);

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:3', 'max:2000'],
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        $ticket->update([
            'status' => TicketStatus::Open,
            'last_reply_at' => now(),
        ]);

        return back()->with('success', 'Reply submitted to CareMate Support.');
    }

    public function profile(): View
    {
        $caregiver = Auth::user()->caregiver;
        $divisions = Location::where('type', 'division')->where('is_active', true)->orderBy('name')->get();
        $districts = Location::where('type', 'district')->where('is_active', true)->orderBy('name')->get();
        $areas = Location::where('type', 'area')->where('is_active', true)->orderBy('name')->get();
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();

        return view('caregiver.profile', compact('caregiver', 'divisions', 'districts', 'areas', 'services'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $caregiver = $user->caregiver;

        if (! $caregiver) {
            return back()->with('error', 'Caregiver profile record not found.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:25', 'unique:users,phone,'.$user->id],
            'caregiver_type' => ['nullable', 'string', 'max:100'],
            'years_experience' => ['nullable', 'integer', 'min:0', 'max:60'],
            'daily_rate' => ['required', 'numeric', 'min:100', 'max:1000000'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'weekly_rate' => ['nullable', 'numeric', 'min:0'],
            'monthly_rate' => ['nullable', 'numeric', 'min:0'],
            'employment_type' => ['nullable', 'in:full_time,part_time,both'],
            'live_type' => ['nullable', 'in:live_in,live_out,both'],
            'preferred_client_gender' => ['nullable', 'in:any,female,male'],
            'about' => ['nullable', 'string', 'max:1500'],
            'bio' => ['nullable', 'string', 'max:3000'],
            'skills' => ['nullable', 'string'],
            'specializations' => ['nullable', 'string'],
            'languages' => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['exists:services,id'],
            'division_id' => ['nullable', 'exists:locations,id'],
            'district_id' => ['nullable', 'exists:locations,id'],
            'area_id' => ['nullable', 'exists:locations,id'],
            'city' => ['nullable', 'string', 'max:80'],
            'present_address' => ['nullable', 'string', 'max:255'],
            'permanent_address' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        $photoFile = $request->file('profile_photo') ?? $request->file('avatar');
        if ($photoFile) {
            $path = $photoFile->store('avatars', 'public');
            $user->update(['avatar_path' => $path]);
        }

        $skillsArray = ! empty($validated['skills'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['skills']))))
            : ($caregiver->skills ?? []);

        $specArray = ! empty($validated['specializations'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['specializations']))))
            : ($caregiver->specializations ?? []);

        $langArray = ! empty($validated['languages'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['languages']))))
            : ($caregiver->languages ?? []);

        $caregiver->update([
            'caregiver_type' => $validated['caregiver_type'] ?? $caregiver->caregiver_type,
            'years_experience' => isset($validated['years_experience']) && $validated['years_experience'] !== '' ? (int) $validated['years_experience'] : $caregiver->years_experience,
            'daily_rate' => $validated['daily_rate'],
            'hourly_rate' => ! empty($validated['hourly_rate']) ? $validated['hourly_rate'] : null,
            'weekly_rate' => ! empty($validated['weekly_rate']) ? $validated['weekly_rate'] : null,
            'monthly_rate' => ! empty($validated['monthly_rate']) ? $validated['monthly_rate'] : null,
            'employment_type' => $validated['employment_type'] ?? $caregiver->employment_type,
            'live_type' => $validated['live_type'] ?? $caregiver->live_type,
            'preferred_client_gender' => $validated['preferred_client_gender'] ?? $caregiver->preferred_client_gender,
            'division_id' => array_key_exists('division_id', $validated) ? $validated['division_id'] : $caregiver->division_id,
            'district_id' => array_key_exists('district_id', $validated) ? $validated['district_id'] : $caregiver->district_id,
            'area_id' => array_key_exists('area_id', $validated) ? $validated['area_id'] : $caregiver->area_id,
            'city' => $validated['city'] ?? $caregiver->city,
            'present_address' => $validated['present_address'] ?? $caregiver->present_address,
            'permanent_address' => $validated['permanent_address'] ?? $caregiver->permanent_address,
            'about' => $validated['about'] ?? $caregiver->about,
            'bio' => $validated['bio'] ?? $caregiver->bio,
            'skills' => $skillsArray,
            'specializations' => $specArray,
            'languages' => $langArray,
        ]);

        if ($request->has('service_ids')) {
            $caregiver->services()->sync($request->input('service_ids', []));
        }

        return back()->with('success', 'Caregiver profile, service rates, and details updated successfully.');
    }
}
