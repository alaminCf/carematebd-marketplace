<?php

namespace App\Models;

use App\Enums\DisputeCategory;
use App\Enums\DisputeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'booking_id',
        'reporter_id',
        'category',
        'description',
        'evidence_path',
        'evidence_name',
        'evidence_mime',
        'evidence_uuid',
        'status',
        'booking_status_before',
        'resolution',
        'resolved_by',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'category' => DisputeCategory::class,
            'status' => DisputeStatus::class,
            'resolved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Dispute $dispute): void {
            if (empty($dispute->reference)) {
                $dispute->reference = 'DSP-'.strtoupper(Str::random(7));
            }
            if (empty($dispute->evidence_uuid)) {
                $dispute->evidence_uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * @return BelongsTo<Booking, $this>
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * @return HasMany<DisputeNote, $this>
     */
    public function notes(): HasMany
    {
        return $this->hasMany(DisputeNote::class)->latest();
    }
}
