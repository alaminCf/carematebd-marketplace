<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum DisputeCategory: string
{
    use HasLabels;

    case ServiceQuality = 'service_quality';
    case NoShow = 'no_show';
    case Payment = 'payment';
    case Behaviour = 'behaviour';
    case Safety = 'safety';
    case Other = 'other';
}
