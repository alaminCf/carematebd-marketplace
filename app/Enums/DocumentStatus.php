<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum DocumentStatus: string
{
    use HasLabels;

    case Pending = 'pending';
    case Verified = 'verified';
    case Rejected = 'rejected';

    public function tone(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Verified => 'success',
            self::Rejected => 'danger',
        };
    }
}
