<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'icon',
        'image_path',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsToMany<Caregiver, $this>
     */
    public function caregivers(): BelongsToMany
    {
        return $this->belongsToMany(Caregiver::class, 'caregiver_services')
            ->withPivot('is_primary')
            ->withTimestamps();
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

    public function imageUrl(): string
    {
        if ($this->image_path) {
            if (str_starts_with($this->image_path, 'http')) {
                return $this->image_path;
            }

            if (str_starts_with($this->image_path, '/storage/') || str_starts_with($this->image_path, 'storage/')) {
                $cleanPath = ltrim((string) preg_replace('#^/?storage/#', '', $this->image_path), '/');

                return url('/media/'.$cleanPath);
            }

            return asset(ltrim($this->image_path, '/'));
        }

        return match ($this->slug) {
            'elderly-care' => asset('images/services/elderly.jpg'),
            'child-care' => asset('images/services/child.jpg'),
            'nursing-care' => asset('images/services/nursing.jpg'),
            'medical-transportation' => asset('images/services/transport.jpg'),
            default => asset('images/hero_caregiver.jpg'),
        };
    }
}
