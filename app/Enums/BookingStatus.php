<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum BookingStatus: string
{
    use HasLabels;

    case Confirmed = 'confirmed';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Disputed = 'disputed';

    public function tone(): string
    {
        return match ($this) {
            self::Confirmed => 'primary',
            self::InProgress => 'info',
            self::Completed => 'success',
            self::Cancelled => 'danger',
            self::Disputed => 'warning',
        };
    }

    /**
     * Statuses that block the caregiver's calendar.
     *
     * @return list<self>
     */
    public static function blocking(): array
    {
        return [self::Confirmed, self::InProgress, self::Disputed];
    }

    public function isActive(): bool
    {
        return in_array($this, [self::Confirmed, self::InProgress], true);
    }
}
