<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaregiverAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'caregiver_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_full_day',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'is_full_day' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Caregiver, $this>
     */
    public function caregiver(): BelongsTo
    {
        return $this->belongsTo(Caregiver::class);
    }

    public function dayName(): string
    {
        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return $days[$this->day_of_week] ?? 'Unknown';
    }
}
