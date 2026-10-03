<?php

namespace App\Models;

use App\Enums\CaregiverStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Caregiver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'slug',
        'gender',
        'date_of_birth',
        'nid_number',
        'nid_hash',
        'present_address',
        'permanent_address',
        'division_id',
        'district_id',
        'area_id',
        'city',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'caregiver_type',
        'years_experience',
        'previous_workplace',
        'previous_experience',
        'skills',
        'specializations',
        'languages',
        'preferred_client_gender',
        'education_qualification',
        'education_institution',
        'education_passing_year',
        'hourly_rate',
        'daily_rate',
        'weekly_rate',
        'monthly_rate',
        'employment_type',
        'live_type',
        'preferred_hours',
        'is_available',
        'about',
        'bio',
        'experience_description',
        'special_skills',
        'status',
        'status_reason',
        'application_step',
        'submitted_at',
        'approved_at',
        'published_at',
        'identity_verified_at',
        'reviewed_by',
        'rating_avg',
        'rating_count',
        'completed_jobs_count',
        'is_featured',
        'sort_order',
        'search_text',
    ];

    protected $hidden = [
        'nid_number',
        'nid_hash',
        'present_address',
        'permanent_address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'nid_number' => 'encrypted',
            'present_address' => 'encrypted',
            'permanent_address' => 'encrypted',
            'emergency_contact_name' => 'encrypted',
            'emergency_contact_phone' => 'encrypted',
            'skills' => 'array',
            'specializations' => 'array',
            'languages' => 'array',
            'years_experience' => 'integer',
            'education_passing_year' => 'integer',
            'hourly_rate' => 'decimal:2',
            'daily_rate' => 'decimal:2',
            'weekly_rate' => 'decimal:2',
            'monthly_rate' => 'decimal:2',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
            'rating_avg' => 'decimal:2',
            'rating_count' => 'integer',
            'completed_jobs_count' => 'integer',
            'application_step' => 'integer',
            'status' => CaregiverStatus::class,
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'published_at' => 'datetime',
            'identity_verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Caregiver $caregiver): void {
            if (empty($caregiver->slug)) {
                $base = Str::slug($caregiver->user?->name ?? 'caregiver');
                $caregiver->slug = $base.'-'.Str::lower(Str::random(5));
            }
        });
    }

    /**
     * Calculate caregiver age in years.
     */
    public function age(): ?int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Scope for publicly searchable & visible caregivers (approved & published).
     */
    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query->whereIn('status', [CaregiverStatus::Approved, CaregiverStatus::Published])
            ->whereHas('user', function (Builder $q): void {
                $q->whereNull('suspended_at');
            });
    }

    public function isVerified(): bool
    {
        return in_array($this->status, [CaregiverStatus::Approved, CaregiverStatus::Published], true);
    }

    public function avatarUrl(): string
    {
        return $this->user?->avatarUrl() ?? 'https://ui-avatars.com/api/?name=Caregiver&background=0ea5e9&color=fff&bold=true';
    }

    public function isPublic(): bool
    {
        return $this->status === CaregiverStatus::Published;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
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
     * @return BelongsToMany<Service, $this>
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'caregiver_services')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function primaryService(): ?Service
    {
        return $this->services()->wherePivot('is_primary', true)->first()
            ?? $this->services()->first();
    }

    /**
     * @return BelongsToMany<Location, $this>
     */
    public function serviceAreas(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'caregiver_service_areas')->withTimestamps();
    }

    /**
     * @return HasMany<CaregiverDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(CaregiverDocument::class);
    }

    /**
     * @return HasMany<CaregiverCertificate, $this>
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(CaregiverCertificate::class);
    }

    /**
     * @return HasMany<CaregiverExperience, $this>
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(CaregiverExperience::class)->orderByDesc('start_year');
    }

    /**
     * @return HasMany<CaregiverAvailability, $this>
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(CaregiverAvailability::class)->orderBy('day_of_week');
    }

    /**
     * @return HasMany<CaregiverBlockedDate, $this>
     */
    public function blockedDates(): HasMany
    {
        return $this->hasMany(CaregiverBlockedDate::class);
    }

    /**
     * @return HasMany<CaregiverVerificationLog, $this>
     */
    public function verificationLogs(): HasMany
    {
        return $this->hasMany(CaregiverVerificationLog::class)->latest();
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
        return $this->hasMany(Review::class)->where('status', 'published');
    }

    /**
     * @return HasMany<CaregiverEarning, $this>
     */
    public function earnings(): HasMany
    {
        return $this->hasMany(CaregiverEarning::class);
    }

    /**
     * @return HasMany<Payout, $this>
     */
    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    public function startingPriceFormatted(): string
    {
        if ($this->daily_rate) {
            return '৳'.number_format($this->daily_rate, 0).'/day';
        }
        if ($this->hourly_rate) {
            return '৳'.number_format($this->hourly_rate, 0).'/hr';
        }
        if ($this->monthly_rate) {
            return '৳'.number_format($this->monthly_rate, 0).'/mo';
        }

        return 'Negotiable';
    }

    public function locationSummary(): string
    {
        $parts = array_filter([$this->area?->name, $this->district?->name, $this->division?->name]);

        return count($parts) ? implode(', ', $parts) : ($this->city ?? 'Bangladesh');
    }

    public function recalculateRating(): void
    {
        $published = $this->reviews()->where('status', 'published');
        $this->rating_count = $published->count();
        $this->rating_avg = $this->rating_count > 0 ? (float) $published->avg('rating') : 0;
        $this->saveQuietly();
    }
}
