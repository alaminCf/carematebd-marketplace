<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Enums\CaregiverStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Caregiver;
use App\Models\HiringRequest;
use App\Models\Location;
use App\Models\Payout;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReportController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->input('start_date', now()->subMonths(1)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $metrics = [
            'total_caregivers' => Caregiver::count(),
            'verified_caregivers' => Caregiver::whereIn('status', [CaregiverStatus::Approved, CaregiverStatus::Published])->count(),
            'total_clients' => User::where('role', 'client')->count(),
            'hiring_requests' => HiringRequest::whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])->count(),
            'completed_bookings' => Booking::where('status', BookingStatus::Completed)->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])->count(),
            'cancelled_bookings' => Booking::where('status', BookingStatus::Cancelled)->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])->count(),
            'gross_revenue' => Booking::where('status', BookingStatus::Completed)->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])->sum('service_amount'),
            'platform_commission' => Booking::where('status', BookingStatus::Completed)->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])->sum('commission_amount'),
            'caregiver_payouts' => Payout::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])->sum('amount'),
        ];

        // Service breakdown
        $serviceBreakdown = Service::withCount(['bookings' => fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate.' 23:59:59'])])
            ->get();

        // Location breakdown (Divisions)
        $locationBreakdown = Location::where('type', 'division')
            ->withCount(['caregivers' => fn ($q) => $q->whereIn('status', [CaregiverStatus::Approved, CaregiverStatus::Published])])
            ->get();

        return view('admin.reports.index', compact('metrics', 'serviceBreakdown', 'locationBreakdown', 'startDate', 'endDate'));
    }
}
