<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum RateType: string
{
    use HasLabels;

    case Hourly = 'hourly';
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
    case Total = 'total';
}
