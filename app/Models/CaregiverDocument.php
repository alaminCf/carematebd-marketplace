<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CaregiverDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'caregiver_id',
        'type',
        'title',
        'path',
        'original_name',
        'mime_type',
        'size',
        'status',
        'verified_by',
        'verified_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => DocumentType::class,
            'status' => DocumentStatus::class,
            'verified_at' => 'datetime',
            'size' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (CaregiverDocument $doc): void {
            if (empty($doc->uuid)) {
                $doc->uuid = (string) Str::uuid();
            }
        });
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
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isNid(): bool
    {
        return in_array($this->type, [DocumentType::NidFront, DocumentType::NidBack], true);
    }
}
