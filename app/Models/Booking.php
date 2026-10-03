<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference',
        'hiring_request_id',
        'client_id',
        'caregiver_id',
        'service_id',
        'start_date',
        'end_date',
        'hours_per_day',
        'division_id',
        'district_id',
        'area_id',
        'rate_type',
        'service_amount',
        'commission_rate',
        'commission_amount',
        'caregiver_amount',
        'status',
        'confirmed_by',
        'confirmed_at',
        'started_at',
        'completion_requested_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'hours_per_day' => 'integer',
            'service_amount' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'caregiver_amount' => 'decimal:2',
            'status' => BookingStatus::class,
            'confirmed_at' => 'datetime',
            'started_at' => 'datetime',
            'completion_requested_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking): void {
            if (empty($booking->reference)) {
                $booking->reference = 'BK-'.strtoupper(Str::random(7));
            }
        });
    }

    /**
     * @return BelongsTo<HiringRequest, $this>
     */
    public function hiringRequest(): BelongsTo
    {
        return $this->belongsTo(HiringRequest::class);
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
    public function confirmedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    /**
     * @return HasOne<Payment, $this>
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * @return HasOne<CaregiverEarning, $this>
     */
    public function earning(): HasOne
    {
        return $this->hasOne(CaregiverEarning::class);
    }

    /**
     * @return HasOne<Review, $this>
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * @return HasMany<Dispute, $this>
     */
    public function disputes(): HasMany
    {
        return $this->hasMany(Dispute::class);
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

    public function canBeReviewedBy(Client $client): bool
    {
        return $this->client_id === $client->id
            && $this->status === BookingStatus::Completed
            && ! $this->review()->exists();
    }

    public function getBookingReferenceAttribute(): string
    {
        return $this->reference ?? 'BK-'.$this->id;
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) $this->service_amount;
    }

    public function getClientTotalAttribute(): float
    {
        return (float) $this->service_amount;
    }

    public function getTotalDaysAttribute(): int
    {
        return $this->daysCount();
    }

    public function getPaymentStatusAttribute(): PaymentStatus
    {
        $status = $this->payment?->status;
        if ($status instanceof PaymentStatus) {
            return $status;
        }

        if (is_string($status) && ! empty($status)) {
            return PaymentStatus::tryFrom($status) ?? PaymentStatus::Pending;
        }

        return PaymentStatus::Pending;
    }
}
