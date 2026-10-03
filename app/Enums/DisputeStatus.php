<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum DisputeStatus: string
{
    use HasLabels;

    case Open = 'open';
    case Investigating = 'investigating';
    case Resolved = 'resolved';
    case Rejected = 'rejected';
    case Closed = 'closed';

    public function tone(): string
    {
        return match ($this) {
            self::Open => 'warning',
            self::Investigating => 'primary',
            self::Resolved => 'success',
            self::Rejected => 'danger',
            self::Closed => 'neutral',
        };
    }

    public function isOpen(): bool
    {
        return in_array($this, [self::Open, self::Investigating], true);
    }
}
