<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'status',
        'avatar_path',
        'email_verified_at',
        'password',
        'last_login_at',
        'last_login_ip',
        'suspended_at',
        'suspension_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'status' => UserStatus::class,
            'last_login_at' => 'datetime',
            'suspended_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isCaregiver(): bool
    {
        return $this->role === UserRole::Caregiver;
    }

    public function isClient(): bool
    {
        return $this->role === UserRole::Client;
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }

    public function isSuspended(): bool
    {
        return $this->status === UserStatus::Suspended;
    }

    public function avatarUrl(): string
    {
        if ($this->avatar_path) {
            if (str_starts_with($this->avatar_path, 'http')) {
                return $this->avatar_path;
            }

            if (str_starts_with($this->avatar_path, '/images/') || str_starts_with($this->avatar_path, 'images/')) {
                return asset(ltrim($this->avatar_path, '/'));
            }

            return asset('storage/'.ltrim($this->avatar_path, '/'));
        }

        // Return a clean UI avatar fallback with user initials
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=0ea5e9&color=fff&bold=true';
    }

    /**
     * @return HasOne<Caregiver, $this>
     */
    public function caregiver(): HasOne
    {
        return $this->hasOne(Caregiver::class);
    }

    /**
     * @return HasOne<Client, $this>
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    /**
     * @return BelongsTo<Role, $this>
     */
    public function roleDefinition(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role', 'slug');
    }

    /**
     * @return HasMany<SupportTicket, $this>
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * @return HasMany<Dispute, $this>
     */
    public function disputesReported(): HasMany
    {
        return $this->hasMany(Dispute::class, 'reporter_id');
    }

    /**
     * @return HasMany<AdminAction, $this>
     */
    public function adminActions(): HasMany
    {
        return $this->hasMany(AdminAction::class, 'admin_id');
    }
}
