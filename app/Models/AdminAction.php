<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action',
        'entity_type',
        'entity_id',
        'previous_state',
        'new_state',
        'reason',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'previous_state' => 'array',
            'new_state' => 'array',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Human-readable label for the action code.
     */
    public function actionLabel(): string
    {
        $map = [
            'caregiver.profile_updated_by_admin' => 'Caregiver Profile Edited',
            'caregiver.approved_and_published' => 'Approved & Published Caregiver',
            'caregiver.changes_requested' => 'Requested Verification Changes',
            'caregiver.rejected' => 'Rejected Caregiver Application',
            'caregiver.suspended' => 'Suspended Caregiver Account',
            'caregiver.reactivated' => 'Reactivated Caregiver Account',
            'caregiver.sort_order_updated' => 'Changed Marketplace Position',
            'caregiver.featured_toggled' => 'Toggled Featured Caregiver',
            'admin.user.created' => 'Created User / Admin Account',
            'admin.user.updated' => 'Edited User / Admin Account',
            'admin.user.status_toggled' => 'Changed User Active Status',
            'admin.user.password_updated' => 'Reset User Password',
            'admin.user.deleted' => 'Deleted User Account',
            'client.suspended' => 'Suspended Client Account',
            'client.reactivated' => 'Reactivated Client Account',
            'service.created' => 'Created Care Service',
            'service.updated' => 'Edited Care Service',
            'service.deleted' => 'Deleted Care Service',
            'location.created' => 'Added Service Location',
            'location.toggled' => 'Toggled Location Status',
            'settings.updated' => 'Updated Platform Settings',
        ];

        if (isset($map[$this->action])) {
            return $map[$this->action];
        }

        return ucwords(str_replace(['.', '_'], ' ', (string) $this->action));
    }

    /**
     * Human-readable field-by-field diff array.
     *
     * @return array<int, array{field: string, from: string, to: string}>
     */
    public function changedFields(): array
    {
        $prev = is_array($this->previous_state) ? $this->previous_state : [];
        $next = is_array($this->new_state) ? $this->new_state : [];

        $keys = array_unique(array_merge(array_keys($prev), array_keys($next)));
        $changes = [];

        foreach ($keys as $key) {
            $oldVal = $prev[$key] ?? null;
            $newVal = $next[$key] ?? null;

            $oldStr = $this->formatDiffValue($oldVal);
            $newStr = $this->formatDiffValue($newVal);

            if ($oldStr === $newStr && $oldVal !== null) {
                continue;
            }

            $changes[] = [
                'field' => ucwords(str_replace('_', ' ', (string) $key)),
                'from' => $oldStr,
                'to' => $newStr,
            ];
        }

        return $changes;
    }

    protected function formatDiffValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '—';
        }
        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }
        if (is_array($value)) {
            return implode(', ', array_map(fn ($v) => is_scalar($v) ? (string) $v : json_encode($v), $value));
        }

        return (string) $value;
    }

    /**
     * Resolve target entity name and admin link.
     *
     * @return array{type: string, label: string, url: ?string}
     */
    public function targetInfo(): array
    {
        if (! $this->entity_type) {
            return [
                'type' => 'System',
                'label' => 'Platform Configuration',
                'url' => route('admin.settings.index'),
            ];
        }

        $base = class_basename($this->entity_type);
        $id = $this->entity_id;

        try {
            if ($base === 'Caregiver' && $id) {
                $cg = Caregiver::with('user')->find($id);
                if ($cg && $cg->user) {
                    return [
                        'type' => 'Caregiver / Nurse',
                        'label' => $cg->user->name.' (#'.$id.')',
                        'url' => route('admin.caregivers.show', $cg->id),
                    ];
                }
            } elseif ($base === 'User' && $id) {
                $u = User::find($id);
                if ($u) {
                    return [
                        'type' => ucfirst($u->role->value).' User',
                        'label' => $u->name.' ('.$u->email.')',
                        'url' => route('admin.users.index', ['search' => $u->email]),
                    ];
                }
            } elseif ($base === 'Booking' && $id) {
                return [
                    'type' => 'Booking',
                    'label' => 'Booking #'.$id,
                    'url' => route('admin.bookings.show', $id),
                ];
            } elseif ($base === 'HiringRequest' && $id) {
                return [
                    'type' => 'Care Request',
                    'label' => 'Request #'.$id,
                    'url' => route('admin.hiring-requests.show', $id),
                ];
            } elseif ($base === 'Service' && $id) {
                $srv = Service::find($id);

                return [
                    'type' => 'Service',
                    'label' => $srv ? $srv->name : 'Service #'.$id,
                    'url' => route('admin.services.index'),
                ];
            }
        } catch (\Throwable) {
            //
        }

        $fallbackName = $this->new_state['name'] ?? $this->previous_state['name'] ?? null;

        return [
            'type' => $base,
            'label' => $fallbackName ? "{$fallbackName} (#{$id})" : "{$base} #{$id}",
            'url' => null,
        ];
    }
}
