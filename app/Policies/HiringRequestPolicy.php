<?php

namespace App\Policies;

use App\Enums\HiringRequestStatus;
use App\Models\HiringRequest;
use App\Models\User;

class HiringRequestPolicy
{
    public function view(User $user, HiringRequest $request): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isClient() && $user->client?->id === $request->client_id) {
            return true;
        }

        // Caregiver can ONLY view if admin has approved & dispatched it
        if ($user->isCaregiver() && $user->caregiver?->id === $request->caregiver_id) {
            return in_array($request->status, HiringRequestStatus::visibleToCaregiver(), true);
        }

        return false;
    }

    public function respond(User $user, HiringRequest $request): bool
    {
        return $user->isCaregiver()
            && $user->caregiver?->id === $request->caregiver_id
            && $request->status === HiringRequestStatus::CaregiverNotified;
    }

    public function adminAction(User $user): bool
    {
        return $user->isAdmin();
    }
}
