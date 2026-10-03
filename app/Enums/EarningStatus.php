<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum EarningStatus: string
{
    use HasLabels;

    case Pending = 'pending';
    case Available = 'available';
    case Cancelled = 'cancelled';

    public function tone(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Available => 'success',
            self::Cancelled => 'danger',
        };
    }
}
