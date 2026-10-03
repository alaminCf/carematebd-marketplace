<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum TicketPriority: string
{
    use HasLabels;

    case Low = 'low';
    case Normal = 'normal';
    case High = 'high';
    case Urgent = 'urgent';

    public function tone(): string
    {
        return match ($this) {
            self::Low => 'neutral',
            self::Normal => 'info',
            self::High => 'warning',
            self::Urgent => 'danger',
        };
    }
}
