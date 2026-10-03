<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum PayoutStatus: string
{
    use HasLabels;

    case Requested = 'requested';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Paid = 'paid';

    public function tone(): string
    {
        return match ($this) {
            self::Requested => 'warning',
            self::Approved => 'primary',
            self::Rejected => 'danger',
            self::Paid => 'success',
        };
    }

    /**
     * Payout statuses that reserve the caregiver's balance.
     *
     * @return list<self>
     */
    public static function reserving(): array
    {
        return [self::Requested, self::Approved];
    }
}
