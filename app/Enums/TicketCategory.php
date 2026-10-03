<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum TicketCategory: string
{
    use HasLabels;

    case General = 'general';
    case Booking = 'booking';
    case Payment = 'payment';
    case Account = 'account';
    case Verification = 'verification';
    case ServiceIssue = 'service_issue';
    case Technical = 'technical';
}
