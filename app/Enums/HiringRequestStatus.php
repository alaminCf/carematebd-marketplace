<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum HiringRequestStatus: string
{
    use HasLabels;

    case PendingAdminReview = 'pending_admin_review';
    case InfoRequested = 'info_requested';
    case AdminApproved = 'admin_approved';
    case CaregiverNotified = 'caregiver_notified';
    case CaregiverAccepted = 'caregiver_accepted';
    case CaregiverDeclined = 'caregiver_declined';
    case AdminConfirmed = 'admin_confirmed';
    case AdminRejected = 'admin_rejected';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PendingAdminReview => 'Pending Admin Review',
            self::InfoRequested => 'More Info Requested',
            self::AdminApproved => 'Admin Approved',
            self::CaregiverNotified => 'Awaiting Caregiver',
            self::CaregiverAccepted => 'Caregiver Accepted',
            self::CaregiverDeclined => 'Caregiver Declined',
            self::AdminConfirmed => 'Booking Confirmed',
            self::AdminRejected => 'Rejected',
            self::Cancelled => 'Cancelled',
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::PendingAdminReview => 'warning',
            self::InfoRequested => 'info',
            self::AdminApproved, self::CaregiverNotified => 'primary',
            self::CaregiverAccepted, self::AdminConfirmed => 'success',
            self::CaregiverDeclined, self::AdminRejected, self::Cancelled => 'danger',
        };
    }

    /**
     * Allowed state-machine transitions.
     *
     * @return list<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::PendingAdminReview => [self::AdminApproved, self::AdminRejected, self::InfoRequested, self::Cancelled, self::PendingAdminReview],
            self::InfoRequested => [self::PendingAdminReview, self::AdminRejected, self::Cancelled, self::AdminApproved],
            self::AdminApproved => [self::CaregiverNotified, self::Cancelled],
            self::CaregiverNotified => [self::CaregiverAccepted, self::CaregiverDeclined, self::Cancelled, self::PendingAdminReview],
            self::CaregiverAccepted => [self::AdminConfirmed, self::Cancelled, self::PendingAdminReview],
            self::CaregiverDeclined => [self::PendingAdminReview, self::AdminRejected, self::Cancelled],
            self::AdminConfirmed, self::AdminRejected, self::Cancelled => [],
        };
    }

    public function canTransitionTo(self $next): bool
    {
        return in_array($next, $this->allowedTransitions(), true);
    }

    public function isOpen(): bool
    {
        return ! in_array($this, [self::AdminConfirmed, self::AdminRejected, self::Cancelled], true);
    }

    /**
     * Statuses in which the caregiver is allowed to see the request.
     *
     * @return list<self>
     */
    public static function visibleToCaregiver(): array
    {
        return [self::CaregiverNotified, self::CaregiverAccepted, self::CaregiverDeclined, self::AdminConfirmed];
    }

    /**
     * Statuses where the admin can (re)assign a caregiver.
     *
     * @return list<self>
     */
    public static function reassignable(): array
    {
        return [self::PendingAdminReview, self::InfoRequested, self::CaregiverDeclined, self::CaregiverNotified, self::CaregiverAccepted];
    }
}
