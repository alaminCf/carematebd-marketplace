<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum PaymentStatus: string
{
    use HasLabels;

    case Pending = 'pending';
    case Processing = 'processing';
    case Paid = 'paid';
    case Failed = 'failed';
    case Refunded = 'refunded';
    case Cancelled = 'cancelled';

    public function tone(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Processing => 'info',
            self::Paid => 'success',
            self::Failed, self::Cancelled => 'danger',
            self::Refunded => 'neutral',
        };
    }
}
