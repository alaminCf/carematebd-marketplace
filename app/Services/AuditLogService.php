<?php

namespace App\Services;

use App\Models\AdminAction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * @param  array<string, mixed>|null  $previousState
     * @param  array<string, mixed>|null  $newState
     */
    public function log(
        User $admin,
        string $action,
        ?Model $entity = null,
        ?array $previousState = null,
        ?array $newState = null,
        ?string $reason = null
    ): AdminAction {
        return AdminAction::create([
            'admin_id' => $admin->id,
            'action' => $action,
            'entity_type' => $entity ? get_class($entity) : null,
            'entity_id' => $entity?->getKey(),
            'previous_state' => $previousState,
            'new_state' => $newState,
            'reason' => $reason,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
