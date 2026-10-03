<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum ReviewStatus: string
{
    use HasLabels;

    case Published = 'published';
    case Hidden = 'hidden';
    case Removed = 'removed';

    public function tone(): string
    {
        return match ($this) {
            self::Published => 'success',
            self::Hidden => 'warning',
            self::Removed => 'danger',
        };
    }
}
