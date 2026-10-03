<?php

namespace App\Http\Controllers\Client;

use App\Enums\BookingStatus;
use App\Enums\DisputeCategory;
use App\Enums\DisputeStatus;
use App\Enums\HiringRequestStatus;
use App\Enums\ReviewStatus;
use App\Enums\TicketCategory;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Caregiver;
use App\Models\Dispute;
use App\Models\HiringRequest;
use App\Models\Location;
use App\Models\Review;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Services\HiringWorkflowService;
use App\Services\PrivacyFilterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ClientDashboardController extends Controller
{
    public function __construct(
        public HiringWorkflowService $workflowService,
        public PrivacyFilterService $privacyService
    ) {}

    public function index(): View
    {
        $client = Auth::user()->client;

        $pendingRequestsCount = HiringRequest::where('client_id', $client->id)
            ->whereIn('status', [HiringRequestStatus::PendingAdminReview, HiringRequestStatus::CaregiverNotified, HiringRequestStatus::CaregiverAccepted])
            ->count();

        $activeBookingsCount = Booking::where('client_id', $client->id)
            ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::InProgress])
            ->count();

        $completedBookingsCount = Booking::where('client_id', $client->id)
            ->where('status', BookingStatus::Completed)
            ->count();

        $savedCaregiversCount = $client->favoriteCaregivers()->count();

        $totalSpending = Booking::where('client_id', $client->id)
            ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::InProgress, BookingStatus::Completed])
            ->sum('service_amount');

        $recentRequests = HiringRequest::where('client_id', $client->id)
            ->with(['caregiver.user', 'service'])
            ->latest()
            ->take(5)
            ->get();

        $recentBookings = Booking::where('client_id', $client->id)
            ->with(['caregiver.user', 'service'])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'active_bookings' => $activeBookingsCount,
            'pending_requests' => $pendingRequestsCount,
            'completed_bookings' => $completedBookingsCount,
            'saved_caregivers' => $savedCaregiversCount,
            'total_spending' => $totalSpending,
        ];

        $activeBookings = Booking::where('client_id', $client->id)
            ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::InProgress])
            ->with(['caregiver.user', 'service'])
            ->latest()
            ->get();

        $recommendedCaregivers = Caregiver::publiclyVisible()
            ->with(['user', 'area', 'district'])
            ->orderByDesc('rating_avg')
            ->take(3)
            ->get();

        return view('client.dashboard', compact(
            'client',
            'stats',
            'activeBookings',
            'recommendedCaregivers',
            'pendingRequestsCount',
            'activeBookingsCount',
            'completedBookingsCount',
            'savedCaregiversCount',
            'totalSpending',
            'recentRequests',
            'recentBookings'
        ));
    }

    public function requests(): View
    {
        $client = Auth::user()->client;
        $requests = HiringRequest::where('client_id', $client->id)
            ->with(['caregiver.user', 'service', 'area', 'district'])
            ->latest()
            ->paginate(10);

        return view('client.requests.index', compact('requests'));
    }

    public function showRequest(HiringRequest $request): View
    {
        Gate::authorize('view', $request);

        $request->load(['caregiver.user', 'service', 'division', 'district', 'area', 'statusLogs', 'booking']);
        $caregiverProfile = $this->privacyService->publicCaregiverProfile($request->caregiver);

        return view('client.requests.show', compact('request', 'caregiverProfile'));
    }

    public function createRequest(Caregiver $caregiver): View|RedirectResponse
    {
        if (! $caregiver->isVerified()) {
            return redirect()->route('marketplace.index')->with('error', 'Caregiver is not currently accepting public requests.');
        }

        $client = Auth::user()->client;
        $divisions = Location::where('type', 'division')->where('is_active', true)->orderBy('name')->get();
        $districts = Location::where('type', 'district')->where('is_active', true)->orderBy('name')->get();
        $areas = Location::where('type', 'area')->where('is_active', true)->orderBy('name')->get();

        return view('client.requests.create', compact('caregiver', 'client', 'divisions', 'districts', 'areas'));
    }

    public function storeRequest(Request $request, Caregiver $caregiver): RedirectResponse
    {
        if (! $caregiver->isVerified()) {
            return redirect()->route('marketplace.index')->with('error', 'Caregiver is not available.');
        }

        $client = Auth::user()->client;

        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'hours_per_day' => ['required', 'integer', 'between:1,24'],
            'division_id' => ['nullable', 'exists:locations,id'],
            'district_id' => ['nullable', 'exists:locations,id'],
            'area_id' => ['nullable', 'exists:locations,id'],
            'service_address' => ['required', 'string', 'max:500'],
            'patient_name' => ['required', 'string', 'max:120'],
            'patient_age' => ['nullable', 'integer', 'between:0,120'],
            'patient_gender' => ['required', 'in:female,male,other,any'],
            'care_requirements' => ['required', 'string', 'min:20', 'max:2000'],
            'special_requirements' => ['nullable', 'string', 'max:1000'],
            'budget' => ['nullable', 'numeric', 'min:500'],
            'budget_type' => ['required', 'in:hourly,daily,weekly,monthly,total'],
            'preferred_schedule' => ['nullable', 'string', 'max:150'],
            'additional_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['caregiver_id'] = $caregiver->id;

        $hiringRequest = $this->workflowService->createRequest($client, $validated);

        return redirect()->route('client.requests.show', $hiringRequest)
            ->with('success', 'Your care request has been sent to CareMate Admin. We will review and verify availability shortly!');
    }

    public function bookings(): View
    {
        $client = Auth::user()->client;
        $bookings = Booking::where('client_id', $client->id)
            ->with(['caregiver.user', 'service', 'area', 'district', 'review'])
            ->latest()
            ->paginate(10);

        return view('client.bookings.index', compact('bookings'));
    }

    public function showBooking(Booking $booking): View
    {
        Gate::authorize('view', $booking);

        $booking->load(['caregiver.user', 'service', 'division', 'district', 'area', 'review', 'payment', 'statusLogs']);
        $caregiverProfile = $this->privacyService->publicCaregiverProfile($booking->caregiver);

        return view('client.bookings.show', compact('booking', 'caregiverProfile'));
    }

    public function storeReview(Request $request, Booking $booking): RedirectResponse
    {
        Gate::authorize('review', $booking);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'professionalism' => ['required', 'integer', 'between:1,5'],
            'communication' => ['required', 'integer', 'between:1,5'],
            'reliability' => ['required', 'integer', 'between:1,5'],
            'care_quality' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $booking): void {
            Review::create([
                'booking_id' => $booking->id,
                'client_id' => $booking->client_id,
                'caregiver_id' => $booking->caregiver_id,
                'rating' => $validated['rating'],
                'professionalism' => $validated['professionalism'],
                'communication' => $validated['communication'],
                'reliability' => $validated['reliability'],
                'care_quality' => $validated['care_quality'],
                'comment' => $validated['comment'],
                'status' => ReviewStatus::Published,
            ]);

            $booking->caregiver->recalculateRating();
        });

        return back()->with('success', 'Thank you! Your verified review has been published.');
    }

    public function favorites(): View
    {
        $client = Auth::user()->client;
        $favorites = $client->favoriteCaregivers()
            ->with(['user', 'services', 'district', 'area'])
            ->paginate(9);

        return view('client.favorites', compact('favorites'));
    }

    public function toggleFavorite(Caregiver $caregiver): RedirectResponse
    {
        $client = Auth::user()->client;
        $client->favoriteCaregivers()->toggle($caregiver->id);

        $isFav = $client->hasFavorited($caregiver->id);

        return back()->with('success', $isFav ? 'Caregiver saved to your favorites.' : 'Caregiver removed from favorites.');
    }

    public function support(): View
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->with(['messages', 'booking'])
            ->latest()
            ->paginate(10);

        $userBookings = Booking::where('client_id', Auth::user()->client->id)->latest()->get();

        return view('client.support.index', compact('tickets', 'userBookings'));
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

        return redirect()->route('client.support.show', $ticket)
            ->with('success', "Support ticket #{$ticket->ticket_number} created. CareMate team will assist you.");
    }

    public function showTicket(SupportTicket $ticket): View
    {
        Gate::authorize('view', $ticket);
        $ticket->load(['messages.user', 'booking.service']);

        return view('client.support.show', compact('ticket'));
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

        return back()->with('success', 'Your reply has been sent to CareMate Support.');
    }

    public function createDispute(Booking $booking): View
    {
        Gate::authorize('view', $booking);

        return view('client.disputes.create', compact('booking'));
    }

    public function storeDispute(Request $request, Booking $booking): RedirectResponse
    {
        Gate::authorize('view', $booking);

        $validated = $request->validate([
            'category' => ['required', 'string'],
            'description' => ['required', 'string', 'min:20', 'max:3000'],
            'evidence' => ['nullable', 'file', 'mimes:jpeg,png,pdf', 'max:5120'],
        ]);

        DB::transaction(function () use ($validated, $booking, $request): void {
            $evidencePath = null;
            $evidenceName = null;
            $evidenceMime = null;

            if ($request->hasFile('evidence')) {
                $file = $request->file('evidence');
                $evidencePath = $file->store('private/disputes', 'local');
                $evidenceName = $file->getClientOriginalName();
                $evidenceMime = $file->getClientMimeType();
            }

            Dispute::create([
                'booking_id' => $booking->id,
                'reporter_id' => Auth::id(),
                'category' => DisputeCategory::tryFrom($validated['category']) ?? DisputeCategory::Other,
                'description' => $validated['description'],
                'evidence_path' => $evidencePath,
                'evidence_name' => $evidenceName,
                'evidence_mime' => $evidenceMime,
                'status' => DisputeStatus::Open,
                'booking_status_before' => $booking->status->value,
            ]);

            $booking->update(['status' => BookingStatus::Disputed]);
        });

        return redirect()->route('client.bookings.show', $booking)
            ->with('warning', 'Dispute ticket registered. CareMate compliance team will investigate and contact you directly.');
    }

    public function profile(): View
    {
        $client = Auth::user()->client;
        $divisions = Location::where('type', 'division')->where('is_active', true)->orderBy('name')->get();
        $districts = Location::where('type', 'district')->where('is_active', true)->orderBy('name')->get();
        $areas = Location::where('type', 'area')->where('is_active', true)->orderBy('name')->get();

        return view('client.profile', compact('client', 'divisions', 'districts', 'areas'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $client = $user->client;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,'.$user->id],
            'present_address' => ['required', 'string', 'max:255'],
            'division_id' => ['nullable', 'exists:locations,id'],
            'district_id' => ['nullable', 'exists:locations,id'],
            'area_id' => ['nullable', 'exists:locations,id'],
            'city' => ['nullable', 'string', 'max:80'],
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:50'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        $client->update([
            'present_address' => $validated['present_address'],
            'division_id' => $validated['division_id'] ?? null,
            'district_id' => $validated['district_id'] ?? null,
            'area_id' => $validated['area_id'] ?? null,
            'city' => $validated['city'] ?? $client->city,
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
            'emergency_contact_relationship' => $validated['emergency_contact_relationship'] ?? null,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }
}
