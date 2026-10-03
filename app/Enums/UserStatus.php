<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum UserStatus: string
{
    use HasLabels;

    case Active = 'active';
    case Suspended = 'suspended';

    public function tone(): string
    {
        return $this === self::Active ? 'success' : 'danger';
    }
}
