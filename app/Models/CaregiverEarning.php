<?php

namespace App\Models;

use App\Enums\EarningStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaregiverEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'caregiver_id',
        'booking_id',
        'gross_amount',
        'commission_amount',
        'net_amount',
        'status',
        'available_at',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'status' => EarningStatus::class,
            'available_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Caregiver, $this>
     */
    public function caregiver(): BelongsTo
    {
        return $this->belongsTo(Caregiver::class);
    }

    /**
     * @return BelongsTo<Booking, $this>
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function getNetCaregiverEarningsAttribute(): float
    {
        return (float) $this->net_amount;
    }

    public function getPlatformCommissionAttribute(): float
    {
        return (float) $this->commission_amount;
    }

    public function getCommissionRatePercentAttribute(): float
    {
        if ($this->gross_amount > 0) {
            return round(((float) $this->commission_amount / (float) $this->gross_amount) * 100, 1);
        }

        return 15.0;
    }
}
