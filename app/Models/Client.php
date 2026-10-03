<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'present_address',
        'division_id',
        'district_id',
        'area_id',
        'city',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
    ];

    protected $hidden = [
        'present_address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
    ];

    protected function casts(): array
    {
        return [
            'present_address' => 'encrypted',
            'emergency_contact_name' => 'encrypted',
            'emergency_contact_phone' => 'encrypted',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
     * @return HasMany<HiringRequest, $this>
     */
    public function hiringRequests(): HasMany
    {
        return $this->hasMany(HiringRequest::class);
    }

    /**
     * @return HasMany<Booking, $this>
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * @return BelongsToMany<Caregiver, $this>
     */
    public function favoriteCaregivers(): BelongsToMany
    {
        return $this->belongsToMany(Caregiver::class, 'favorites')->withTimestamps();
    }

    public function hasFavorited(int $caregiverId): bool
    {
        return $this->favoriteCaregivers()->where('caregivers.id', $caregiverId)->exists();
    }
}
