<?php

namespace App\Models;

use App\Enums\ReviewStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'client_id',
        'caregiver_id',
        'rating',
        'professionalism',
        'communication',
        'reliability',
        'care_quality',
        'comment',
        'status',
        'is_reported',
        'report_reason',
        'moderated_by',
        'moderated_at',
        'moderation_note',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'professionalism' => 'integer',
            'communication' => 'integer',
            'reliability' => 'integer',
            'care_quality' => 'integer',
            'is_reported' => 'boolean',
            'status' => ReviewStatus::class,
            'moderated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Booking, $this>
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
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
     * @return BelongsTo<User, $this>
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }
}
