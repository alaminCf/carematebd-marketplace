<?php

namespace App\Models;

use App\Enums\HiringRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HiringRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'client_id',
        'caregiver_id',
        'service_id',
        'start_date',
        'end_date',
        'hours_per_day',
        'division_id',
        'district_id',
        'area_id',
        'service_address',
        'patient_name',
        'patient_age',
        'patient_gender',
        'care_requirements',
        'special_requirements',
        'budget',
        'budget_type',
        'preferred_schedule',
        'additional_notes',
        'status',
        'admin_message',
        'caregiver_brief',
        'client_response',
        'caregiver_response_note',
        'reviewed_by',
        'reviewed_at',
        'caregiver_notified_at',
        'caregiver_responded_at',
    ];

    protected $hidden = [
        'service_address',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'hours_per_day' => 'integer',
            'patient_age' => 'integer',
            'budget' => 'decimal:2',
            'status' => HiringRequestStatus::class,
            'service_address' => 'encrypted',
            'reviewed_at' => 'datetime',
            'caregiver_notified_at' => 'datetime',
            'caregiver_responded_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (HiringRequest $hr): void {
            if (empty($hr->reference)) {
                $hr->reference = 'REQ-'.strtoupper(Str::random(7));
            }
        });
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo<Caregiver, $this>
     */
    public function caregiver(): BelongsTo
    {
        return $this->belongsTo(Caregiver::class);
    }

    /**
     * @return BelongsTo<Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * @return BelongsTo<Location, $this>
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'division_id');
    }

    /**
     * @return BelongsTo<Location, $this>
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'district_id');
    }

    /**
     * @return BelongsTo<Location, $this>
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'area_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * @return HasMany<HiringRequestNote, $this>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(HiringRequestNote::class)->latest();
    }

    /**
     * @return HasOne<Booking, $this>
     */
    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }

    /**
     * @return HasMany<BookingStatusLog, $this>
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(BookingStatusLog::class)->latest();
    }

    public function daysCount(): int
    {
        return max(1, $this->start_date->diffInDays($this->end_date) + 1);
    }

    public function generalLocation(): string
    {
        $parts = array_filter([$this->area?->name, $this->district?->name, $this->division?->name]);

        return count($parts) ? implode(', ', $parts) : 'Dhaka';
    }
}
