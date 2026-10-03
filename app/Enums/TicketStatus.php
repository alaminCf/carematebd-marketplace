<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum TicketStatus: string
{
    use HasLabels;

    case Open = 'open';
    case InReview = 'in_review';
    case WaitingForUser = 'waiting_for_user';
    case Resolved = 'resolved';
    case Closed = 'closed';

    public function tone(): string
    {
        return match ($this) {
            self::Open => 'warning',
            self::InReview => 'primary',
            self::WaitingForUser => 'info',
            self::Resolved => 'success',
            self::Closed => 'neutral',
        };
    }
}
