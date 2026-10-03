<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\EarningStatus;
use App\Enums\HiringRequestStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\Caregiver;
use App\Models\CaregiverEarning;
use App\Models\Client;
use App\Models\HiringRequest;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\CareMateDatabaseNotification;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class HiringWorkflowService
{
    public function __construct(
        public CommissionService $commissionService,
        public AuditLogService $auditLogService
    ) {}

    /**
     * Client submits a care request.
     *
     * @param  array<string, mixed>  $data
     */
    public function createRequest(Client $client, array $data): HiringRequest
    {
        return DB::transaction(function () use ($client, $data): HiringRequest {
            $request = HiringRequest::create([
                'client_id' => $client->id,
                'caregiver_id' => $data['caregiver_id'],
                'service_id' => $data['service_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'hours_per_day' => $data['hours_per_day'] ?? 8,
                'division_id' => $data['division_id'] ?? null,
                'district_id' => $data['district_id'] ?? null,
                'area_id' => $data['area_id'] ?? null,
                'service_address' => $data['service_address'] ?? null,
                'patient_name' => $data['patient_name'],
                'patient_age' => $data['patient_age'] ?? null,
                'patient_gender' => $data['patient_gender'] ?? 'any',
                'care_requirements' => $data['care_requirements'],
                'special_requirements' => $data['special_requirements'] ?? null,
                'budget' => $data['budget'] ?? null,
                'budget_type' => $data['budget_type'] ?? 'daily',
                'preferred_schedule' => $data['preferred_schedule'] ?? null,
                'additional_notes' => $data['additional_notes'] ?? null,
                'status' => HiringRequestStatus::PendingAdminReview,
            ]);

            BookingStatusLog::create([
                'hiring_request_id' => $request->id,
                'user_id' => $client->user_id,
                'from_status' => null,
                'to_status' => HiringRequestStatus::PendingAdminReview->value,
                'note' => 'Hiring request submitted by client.',
            ]);

            // Notify all admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new CareMateDatabaseNotification(
                    title: 'New Care Request: #'.$request->reference,
                    body: "Patient: {$request->patient_name} in {$request->generalLocation()}. Awaiting your review.",
                    actionUrl: route('admin.hiring-requests.show', $request),
                    type: 'new_hiring_request'
                ));
            }

            // Notify client
            $client->user->notify(new CareMateDatabaseNotification(
                title: 'Care Request Submitted: #'.$request->reference,
                body: 'Your care request has been received by CareMate. Our care team is reviewing it.',
                actionUrl: route('client.requests.show', $request),
                type: 'request_submitted'
            ));

            return $request;
        });
    }

    /**
     * Admin approves request and forwards sanitized brief to the caregiver.
     */
    public function adminApprove(
        HiringRequest $request,
        User $admin,
        ?string $adminMessage = null,
        ?string $caregiverBrief = null
    ): void {
        DB::transaction(function () use ($request, $admin, $adminMessage, $caregiverBrief): void {
            $prev = $request->status->value;

            $request->update([
                'status' => HiringRequestStatus::CaregiverNotified,
                'admin_message' => $adminMessage,
                'caregiver_brief' => $caregiverBrief,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
                'caregiver_notified_at' => now(),
            ]);

            BookingStatusLog::create([
                'hiring_request_id' => $request->id,
                'user_id' => $admin->id,
                'from_status' => $prev,
                'to_status' => HiringRequestStatus::CaregiverNotified->value,
                'note' => 'Admin approved and dispatched request to caregiver.',
            ]);

            $this->auditLogService->log(
                $admin,
                'hiring_request.approved',
                $request,
                ['status' => $prev],
                ['status' => HiringRequestStatus::CaregiverNotified->value],
                'Admin approved client request'
            );

            // Caregiver notification (in-app)
            $request->caregiver->user->notify(new CareMateDatabaseNotification(
                title: 'New Care Request: #'.$request->reference,
                body: "CareMate has assigned you an approved request in {$request->generalLocation()}. Please review and respond.",
                actionUrl: route('caregiver.requests.show', $request),
                type: 'new_care_request'
            ));

            // Client notification
            $request->client->user->notify(new CareMateDatabaseNotification(
                title: 'Request Approved by CareMate: #'.$request->reference,
                body: 'CareMate Admin verified your request. It has been presented to the caregiver for confirmation.',
                actionUrl: route('client.requests.show', $request),
                type: 'request_approved_by_admin'
            ));
        });
    }

    /**
     * Admin requests more information from the client.
     */
    public function adminRequestMoreInfo(HiringRequest $request, User $admin, string $message): void
    {
        DB::transaction(function () use ($request, $admin, $message): void {
            $prev = $request->status->value;
            $request->update([
                'status' => HiringRequestStatus::InfoRequested,
                'admin_message' => $message,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
            ]);

            BookingStatusLog::create([
                'hiring_request_id' => $request->id,
                'user_id' => $admin->id,
                'from_status' => $prev,
                'to_status' => HiringRequestStatus::InfoRequested->value,
                'note' => 'Admin requested more information: '.$message,
            ]);

            $request->client->user->notify(new CareMateDatabaseNotification(
                title: 'Action Needed: More Details Requested',
                body: "CareMate Admin requires more details on request #{$request->reference}: {$message}",
                actionUrl: route('client.requests.show', $request),
                type: 'info_requested'
            ));
        });
    }

    /**
     * Admin rejects the request with an explanation.
     */
    public function adminReject(HiringRequest $request, User $admin, string $reason): void
    {
        DB::transaction(function () use ($request, $admin, $reason): void {
            $prev = $request->status->value;
            $request->update([
                'status' => HiringRequestStatus::AdminRejected,
                'admin_message' => $reason,
                'reviewed_by' => $admin->id,
                'reviewed_at' => now(),
            ]);

            BookingStatusLog::create([
                'hiring_request_id' => $request->id,
                'user_id' => $admin->id,
                'from_status' => $prev,
                'to_status' => HiringRequestStatus::AdminRejected->value,
                'note' => 'Admin rejected request: '.$reason,
            ]);

            $this->auditLogService->log(
                $admin,
                'hiring_request.rejected',
                $request,
                ['status' => $prev],
                ['status' => HiringRequestStatus::AdminRejected->value],
                $reason
            );

            $request->client->user->notify(new CareMateDatabaseNotification(
                title: 'Request Update: #'.$request->reference,
                body: "Your care request could not be fulfilled: {$reason}",
                actionUrl: route('client.requests.show', $request),
                type: 'request_rejected'
            ));
        });
    }

    /**
     * Admin reassigns a different caregiver to the request.
     */
    public function adminReassign(
        HiringRequest $request,
        Caregiver $newCaregiver,
        User $admin,
        ?string $reason = null
    ): void {
        DB::transaction(function () use ($request, $newCaregiver, $admin, $reason): void {
            $prevCaregiverId = $request->caregiver_id;

            $request->update([
                'caregiver_id' => $newCaregiver->id,
                'status' => HiringRequestStatus::CaregiverNotified,
                'caregiver_notified_at' => now(),
                'caregiver_responded_at' => null,
                'caregiver_response_note' => null,
            ]);

            BookingStatusLog::create([
                'hiring_request_id' => $request->id,
                'user_id' => $admin->id,
                'from_status' => $request->status->value,
                'to_status' => HiringRequestStatus::CaregiverNotified->value,
                'note' => "Admin reassigned request to Caregiver #{$newCaregiver->id}. ".($reason ? "Reason: {$reason}" : ''),
            ]);

            $this->auditLogService->log(
                $admin,
                'hiring_request.reassigned',
                $request,
                ['caregiver_id' => $prevCaregiverId],
                ['caregiver_id' => $newCaregiver->id],
                $reason
            );

            $newCaregiver->user->notify(new CareMateDatabaseNotification(
                title: 'New Care Request: #'.$request->reference,
                body: "CareMate has assigned you an approved request in {$request->generalLocation()}. Please review.",
                actionUrl: route('caregiver.requests.show', $request),
                type: 'new_care_request'
            ));
        });
    }

    /**
     * Caregiver accepts the request.
     */
    public function caregiverAccept(HiringRequest $request, Caregiver $caregiver, ?string $note = null): void
    {
        if ($request->caregiver_id !== $caregiver->id) {
            throw new InvalidArgumentException('Unauthorized caregiver for this request.');
        }

        DB::transaction(function () use ($request, $caregiver, $note): void {
            $prev = $request->status->value;

            $request->update([
                'status' => HiringRequestStatus::CaregiverAccepted,
                'caregiver_responded_at' => now(),
                'caregiver_response_note' => $note,
            ]);

            BookingStatusLog::create([
                'hiring_request_id' => $request->id,
                'user_id' => $caregiver->user_id,
                'from_status' => $prev,
                'to_status' => HiringRequestStatus::CaregiverAccepted->value,
                'note' => 'Caregiver accepted the request. '.($note ? "Note: {$note}" : ''),
            ]);

            // Notify Admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new CareMateDatabaseNotification(
                    title: 'Caregiver Accepted: #'.$request->reference,
                    body: "{$caregiver->user->name} accepted request #{$request->reference}. Please finalize and confirm booking.",
                    actionUrl: route('admin.hiring-requests.show', $request),
                    type: 'caregiver_accepted'
                ));
            }

            // Notify Client (No caregiver contact information given)
            $request->client->user->notify(new CareMateDatabaseNotification(
                title: 'Caregiver Accepted: #'.$request->reference,
                body: 'The assigned verified caregiver has accepted your service request. CareMate Admin is finalizing your booking confirmation.',
                actionUrl: route('client.requests.show', $request),
                type: 'caregiver_accepted'
            ));
        });
    }

    /**
     * Caregiver declines the request.
     */
    public function caregiverDecline(HiringRequest $request, Caregiver $caregiver, ?string $reason = null): void
    {
        if ($request->caregiver_id !== $caregiver->id) {
            throw new InvalidArgumentException('Unauthorized caregiver for this request.');
        }

        DB::transaction(function () use ($request, $caregiver, $reason): void {
            $prev = $request->status->value;

            $request->update([
                'status' => HiringRequestStatus::CaregiverDeclined,
                'caregiver_responded_at' => now(),
                'caregiver_response_note' => $reason,
            ]);

            BookingStatusLog::create([
                'hiring_request_id' => $request->id,
                'user_id' => $caregiver->user_id,
                'from_status' => $prev,
                'to_status' => HiringRequestStatus::CaregiverDeclined->value,
                'note' => 'Caregiver declined the request. '.($reason ? "Reason: {$reason}" : ''),
            ]);

            // Notify Admin to reassign or resolve
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new CareMateDatabaseNotification(
                    title: 'Caregiver Declined: #'.$request->reference,
                    body: "{$caregiver->user->name} declined request #{$request->reference}. Please reassign another caregiver.",
                    actionUrl: route('admin.hiring-requests.show', $request),
                    type: 'caregiver_declined'
                ));
            }
        });
    }

    /**
     * Admin confirms the final booking and records financial split.
     *
     * @param  array<string, mixed>  $financials
     */
    public function adminConfirmBooking(
        HiringRequest $request,
        User $admin,
        array $financials = []
    ): Booking {
        return DB::transaction(function () use ($request, $admin, $financials): Booking {
            $days = $request->daysCount();
            $dailyRate = (float) ($financials['daily_rate'] ?? $request->caregiver->daily_rate ?? 1200);
            $serviceAmount = (float) ($financials['service_amount'] ?? ($days * $dailyRate));

            $split = $this->commissionService->calculate(
                $serviceAmount,
                isset($financials['commission_rate']) ? (float) $financials['commission_rate'] : null
            );

            $request->update([
                'status' => HiringRequestStatus::AdminConfirmed,
            ]);

            $booking = Booking::create([
                'hiring_request_id' => $request->id,
                'client_id' => $request->client_id,
                'caregiver_id' => $request->caregiver_id,
                'service_id' => $request->service_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'hours_per_day' => $request->hours_per_day,
                'division_id' => $request->division_id,
                'district_id' => $request->district_id,
                'area_id' => $request->area_id,
                'rate_type' => 'daily',
                'service_amount' => $split['service_amount'],
                'commission_rate' => $split['commission_rate'],
                'commission_amount' => $split['commission_amount'],
                'caregiver_amount' => $split['caregiver_amount'],
                'status' => BookingStatus::Confirmed,
                'confirmed_by' => $admin->id,
                'confirmed_at' => now(),
            ]);

            // Create initial pending earning for caregiver
            CaregiverEarning::create([
                'caregiver_id' => $request->caregiver_id,
                'booking_id' => $booking->id,
                'gross_amount' => $split['service_amount'],
                'commission_amount' => $split['commission_amount'],
                'net_amount' => $split['caregiver_amount'],
                'status' => EarningStatus::Pending,
            ]);

            // Create pending payment record
            Payment::create([
                'booking_id' => $booking->id,
                'client_id' => $request->client_id,
                'amount' => $split['service_amount'],
                'commission_amount' => $split['commission_amount'],
                'caregiver_amount' => $split['caregiver_amount'],
                'status' => PaymentStatus::Pending,
                'method' => null,
                'recorded_by' => $admin->id,
            ]);

            BookingStatusLog::create([
                'hiring_request_id' => $request->id,
                'booking_id' => $booking->id,
                'user_id' => $admin->id,
                'from_status' => HiringRequestStatus::CaregiverAccepted->value,
                'to_status' => BookingStatus::Confirmed->value,
                'note' => "Admin confirmed booking #{$booking->reference}. Service Amount: ৳{$split['service_amount']} (Commission: ৳{$split['commission_amount']}, Caregiver: ৳{$split['caregiver_amount']}).",
            ]);

            $this->auditLogService->log(
                $admin,
                'booking.confirmed',
                $booking,
                null,
                ['status' => BookingStatus::Confirmed->value, 'service_amount' => $split['service_amount']],
                'Admin confirmed booking'
            );

            // Notify caregiver
            $request->caregiver->user->notify(new CareMateDatabaseNotification(
                title: 'Booking Confirmed: #'.$booking->reference,
                body: "Your booking for {$request->service->name} from {$booking->start_date->format('M d')} to {$booking->end_date->format('M d')} has been officially confirmed by CareMate.",
                actionUrl: route('caregiver.jobs.show', $booking),
                type: 'booking_confirmed'
            ));

            // Notify client
            $request->client->user->notify(new CareMateDatabaseNotification(
                title: 'Booking Confirmed: #'.$booking->reference,
                body: "Your care service #{$booking->reference} is confirmed! CareMate will coordinate all service arrangements.",
                actionUrl: route('client.bookings.show', $booking),
                type: 'booking_confirmed'
            ));

            return $booking;
        });
    }

    /**
     * Mark booking in progress.
     */
    public function startService(Booking $booking): void
    {
        DB::transaction(function () use ($booking): void {
            $prev = $booking->status->value;
            $booking->update([
                'status' => BookingStatus::InProgress,
                'started_at' => now(),
            ]);

            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'from_status' => $prev,
                'to_status' => BookingStatus::InProgress->value,
                'note' => 'Service is now in progress.',
            ]);
        });
    }

    /**
     * Mark booking as completed, unlock caregiver earnings, and prompt client review.
     */
    public function completeService(Booking $booking, User $actor): void
    {
        DB::transaction(function () use ($booking, $actor): void {
            $prev = $booking->status->value;

            $booking->update([
                'status' => BookingStatus::Completed,
                'completed_at' => now(),
            ]);

            // Release caregiver earning
            $earning = $booking->earning;
            if ($earning && $earning->status === EarningStatus::Pending) {
                $earning->update([
                    'status' => EarningStatus::Available,
                    'available_at' => now(),
                ]);
            }

            // Increment caregiver completed jobs
            $booking->caregiver->increment('completed_jobs_count');

            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'user_id' => $actor->id,
                'from_status' => $prev,
                'to_status' => BookingStatus::Completed->value,
                'note' => "Booking marked as completed by {$actor->name}.",
            ]);

            $this->auditLogService->log(
                $actor,
                'booking.completed',
                $booking,
                ['status' => $prev],
                ['status' => BookingStatus::Completed->value],
                'Service completed successfully'
            );

            // Notify client to review
            $booking->client->user->notify(new CareMateDatabaseNotification(
                title: 'Service Completed: Rate Your Caregiver',
                body: "Care service #{$booking->reference} has completed. Please leave a review for your caregiver to help maintain high quality on CareMate BD.",
                actionUrl: route('client.bookings.show', $booking),
                type: 'review_reminder'
            ));

            // Notify caregiver about earnings release
            $booking->caregiver->user->notify(new CareMateDatabaseNotification(
                title: 'Earnings Credited: ৳'.number_format($booking->caregiver_amount, 0),
                body: "Service #{$booking->reference} completed. Your net earnings have been credited to your available balance.",
                actionUrl: route('caregiver.earnings'),
                type: 'earnings_available'
            ));
        });
    }

    /**
     * Cancel booking.
     */
    public function cancelBooking(Booking $booking, User $actor, string $reason): void
    {
        DB::transaction(function () use ($booking, $actor, $reason): void {
            $prev = $booking->status->value;

            $booking->update([
                'status' => BookingStatus::Cancelled,
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
            ]);

            // Cancel earning if still pending
            if ($booking->earning && $booking->earning->status === EarningStatus::Pending) {
                $booking->earning->update(['status' => EarningStatus::Cancelled]);
            }

            BookingStatusLog::create([
                'booking_id' => $booking->id,
                'user_id' => $actor->id,
                'from_status' => $prev,
                'to_status' => BookingStatus::Cancelled->value,
                'note' => "Booking cancelled. Reason: {$reason}",
            ]);

            $this->auditLogService->log(
                $actor,
                'booking.cancelled',
                $booking,
                ['status' => $prev],
                ['status' => BookingStatus::Cancelled->value],
                $reason
            );

            $booking->client->user->notify(new CareMateDatabaseNotification(
                title: 'Booking Cancelled: #'.$booking->reference,
                body: "Booking #{$booking->reference} has been cancelled: {$reason}",
                actionUrl: route('client.bookings.show', $booking),
                type: 'booking_cancelled'
            ));

            $booking->caregiver->user->notify(new CareMateDatabaseNotification(
                title: 'Booking Cancelled: #'.$booking->reference,
                body: "Booking #{$booking->reference} has been cancelled: {$reason}",
                actionUrl: route('caregiver.jobs.show', $booking),
                type: 'booking_cancelled'
            ));
        });
    }
}
