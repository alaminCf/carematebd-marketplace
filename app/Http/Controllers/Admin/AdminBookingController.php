<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\HiringWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminBookingController extends Controller
{
    public function __construct(
        public HiringWorkflowService $workflowService
    ) {}

    public function index(Request $request): View
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $query = Booking::with(['client.user', 'caregiver.user', 'service', 'payment']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('client.user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('caregiver.user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings', 'status', 'search'));
    }

    public function show(Booking $booking): View
    {
        $booking->load([
            'client.user',
            'caregiver.user',
            'service',
            'division',
            'district',
            'area',
            'payment',
            'earning',
            'review',
            'disputes',
            'statusLogs.user',
            'hiringRequest',
        ]);

        return view('admin.bookings.show', compact('booking'));
    }

    public function start(Booking $booking): RedirectResponse
    {
        $this->workflowService->startService($booking);

        return back()->with('success', 'Booking marked as In Progress.');
    }

    public function complete(Booking $booking): RedirectResponse
    {
        $this->workflowService->completeService($booking, Auth::user());

        return back()->with('success', 'Booking marked as Completed. Caregiver earnings credited to available balance.');
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $this->workflowService->cancelBooking($booking, Auth::user(), $validated['reason']);

        return back()->with('warning', 'Booking cancelled.');
    }
}
