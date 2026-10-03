<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caregiver;
use App\Models\HiringRequest;
use App\Models\HiringRequestNote;
use App\Services\AuditLogService;
use App\Services\HiringWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminHiringRequestController extends Controller
{
    public function __construct(
        public HiringWorkflowService $workflowService,
        public AuditLogService $auditLogService
    ) {}

    public function index(Request $request): View
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = HiringRequest::with(['client.user', 'caregiver.user', 'service', 'district', 'area']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('patient_name', 'like', "%{$search}%")
                    ->orWhereHas('client.user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('caregiver.user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        return view('admin.hiring-requests.index', compact('requests', 'status', 'search'));
    }

    public function show(HiringRequest $hiringRequest): View
    {
        $hiringRequest->load([
            'client.user',
            'caregiver.user',
            'service',
            'division',
            'district',
            'area',
            'notes.admin',
            'statusLogs.user',
            'booking',
        ]);

        $availableCaregivers = Caregiver::publiclyVisible()
            ->where('id', '!=', $hiringRequest->caregiver_id)
            ->whereHas('services', fn ($q) => $q->where('services.id', $hiringRequest->service_id))
            ->with('user')
            ->get();

        return view('admin.hiring-requests.show', compact('hiringRequest', 'availableCaregivers'));
    }

    public function approve(Request $request, HiringRequest $hiringRequest): RedirectResponse
    {
        $validated = $request->validate([
            'caregiver_brief' => ['nullable', 'string', 'max:1000'],
            'admin_message' => ['nullable', 'string', 'max:500'],
        ]);

        $this->workflowService->adminApprove(
            $hiringRequest,
            Auth::user(),
            $validated['admin_message'] ?? null,
            $validated['caregiver_brief'] ?? null
        );

        return back()->with('success', 'Request approved by Admin and dispatched to caregiver.');
    }

    public function requestMoreInfo(Request $request, HiringRequest $hiringRequest): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $this->workflowService->adminRequestMoreInfo($hiringRequest, Auth::user(), $validated['message']);

        return back()->with('info', 'Information request sent to client.');
    }

    public function reject(Request $request, HiringRequest $hiringRequest): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:1000'],
        ]);

        $this->workflowService->adminReject($hiringRequest, Auth::user(), $validated['reason']);

        return back()->with('warning', 'Hiring request rejected.');
    }

    public function reassign(Request $request, HiringRequest $hiringRequest): RedirectResponse
    {
        $validated = $request->validate([
            'caregiver_id' => ['required', 'exists:caregivers,id'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $newCaregiver = Caregiver::findOrFail($validated['caregiver_id']);

        $this->workflowService->adminReassign($hiringRequest, $newCaregiver, Auth::user(), $validated['reason'] ?? null);

        return back()->with('success', "Caregiver reassigned to {$newCaregiver->user->name}.");
    }

    public function confirmBooking(Request $request, HiringRequest $hiringRequest): RedirectResponse
    {
        $validated = $request->validate([
            'daily_rate' => ['required', 'numeric', 'min:100'],
            'service_amount' => ['required', 'numeric', 'min:100'],
            'commission_rate' => ['required', 'numeric', 'between:0,50'],
        ]);

        $booking = $this->workflowService->adminConfirmBooking($hiringRequest, Auth::user(), $validated);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', "Booking #{$booking->reference} officially confirmed by Admin!");
    }

    public function addNote(Request $request, HiringRequest $hiringRequest): RedirectResponse
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:1000'],
        ]);

        HiringRequestNote::create([
            'hiring_request_id' => $hiringRequest->id,
            'admin_id' => Auth::id(),
            'note' => $validated['note'],
        ]);

        return back()->with('success', 'Internal note added.');
    }
}
