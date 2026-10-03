<?php

namespace App\Enums;

use App\Enums\Concerns\HasLabels;

enum LocationType: string
{
    use HasLabels;

    case Division = 'division';
    case District = 'district';
    case Area = 'area';
}
