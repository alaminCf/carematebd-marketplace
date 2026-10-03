<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum UserRole: string
{
    use HasLabels;

    case Admin = 'admin';
    case Caregiver = 'caregiver';
    case Client = 'client';

    public function dashboardRoute(): string
    {
        return match ($this) {
            self::Admin => 'admin.dashboard',
            self::Caregiver => 'caregiver.dashboard',
            self::Client => 'client.dashboard',
        };
    }
}
