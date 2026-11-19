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
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;

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
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name ?: 'CareMate').'&background=0ea5e9&color=fff&bold=true';
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatarUrl();
    }

    public static function storePublicAvatar(UploadedFile $file, string $folder = 'avatars'): string
    {
        $path = $file->store($folder, 'public');

        try {
            $publicTarget = public_path('storage/'.$path);
            File::ensureDirectoryExists(dirname($publicTarget));
            File::copy(storage_path('app/public/'.$path), $publicTarget);
        } catch (\Throwable) {
            // Ignore if public_path('storage') is a symlink or read-only
        }

        return $path;
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
