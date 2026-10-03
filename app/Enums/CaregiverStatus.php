<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum CaregiverStatus: string
{
    use HasLabels;

    case Draft = 'draft';
    case PendingVerification = 'pending_verification';
    case UnderReview = 'under_review';
    case ChangesRequired = 'changes_required';
    case Approved = 'approved';
    case Published = 'published';
    case Rejected = 'rejected';
    case Suspended = 'suspended';

    public function tone(): string
    {
        return match ($this) {
            self::Draft => 'neutral',
            self::PendingVerification, self::UnderReview => 'warning',
            self::ChangesRequired => 'info',
            self::Approved, self::Published => 'success',
            self::Rejected, self::Suspended => 'danger',
        };
    }

    /**
     * Whether the caregiver may still edit their application.
     */
    public function isEditable(): bool
    {
        return in_array($this, [self::Draft, self::ChangesRequired], true);
    }

    /**
     * Statuses that appear in the admin verification queue.
     *
     * @return list<self>
     */
    public static function awaitingReview(): array
    {
        return [self::PendingVerification, self::UnderReview];
    }
}
