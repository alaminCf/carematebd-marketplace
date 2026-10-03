<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BookingStatus;
use App\Enums\CaregiverStatus;
use App\Enums\DisputeStatus;
use App\Enums\HiringRequestStatus;
use App\Enums\PayoutStatus;
use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Caregiver;
use App\Models\Dispute;
use App\Models\HiringRequest;
use App\Models\Payout;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_caregivers' => Caregiver::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'pending_applications' => Caregiver::whereIn('status', [CaregiverStatus::PendingVerification, CaregiverStatus::UnderReview])->count(),
            'pending_caregivers' => Caregiver::whereIn('status', [CaregiverStatus::PendingVerification, CaregiverStatus::UnderReview])->count(),
            'approved_caregivers' => Caregiver::where('status', CaregiverStatus::Approved)->count(),
            'published_caregivers' => Caregiver::where('status', CaregiverStatus::Published)->count(),
            'pending_hiring_requests' => HiringRequest::where('status', HiringRequestStatus::PendingAdminReview)->count(),
            'active_bookings' => Booking::whereIn('status', [BookingStatus::Confirmed, BookingStatus::InProgress])->count(),
            'completed_bookings' => Booking::where('status', BookingStatus::Completed)->count(),
            'total_revenue' => Booking::where('status', BookingStatus::Completed)->sum('service_amount'),
            'total_volume' => Booking::where('status', BookingStatus::Completed)->sum('service_amount'),
            'total_commission' => Booking::where('status', BookingStatus::Completed)->sum('commission_amount'),
            'platform_revenue' => Booking::where('status', BookingStatus::Completed)->sum('commission_amount'),
            'pending_payouts' => Payout::where('status', PayoutStatus::Requested)->count(),
            'open_support_tickets' => SupportTicket::whereIn('status', [TicketStatus::Open, TicketStatus::InReview])->count(),
            'open_tickets' => SupportTicket::whereIn('status', [TicketStatus::Open, TicketStatus::InReview])->count(),
            'active_disputes' => Dispute::whereIn('status', [DisputeStatus::Open, DisputeStatus::Investigating])->count(),
        ];

        $pendingApplications = Caregiver::whereIn('status', [CaregiverStatus::PendingVerification, CaregiverStatus::UnderReview])
            ->with(['user', 'services', 'district'])
            ->latest('submitted_at')
            ->take(5)
            ->get();

        $pendingRequests = HiringRequest::where('status', HiringRequestStatus::PendingAdminReview)
            ->with(['client.user', 'caregiver.user', 'service', 'area', 'district'])
            ->latest()
            ->take(5)
            ->get();

        $recentBookings = Booking::with(['client.user', 'caregiver.user', 'service'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingApplications', 'pendingRequests', 'recentBookings'));
    }
}
