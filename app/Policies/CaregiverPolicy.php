<?php

namespace App\Policies;

use App\Models\Caregiver;
use App\Models\User;

class CaregiverPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(?User $user, Caregiver $caregiver): bool
    {
        // Publicly visible if published/approved
        if ($caregiver->isVerified()) {
            return true;
        }

        if (! $user) {
            return false;
        }

        return $user->isAdmin() || ($user->isCaregiver() && $user->caregiver?->id === $caregiver->id);
    }

    public function update(User $user, Caregiver $caregiver): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isCaregiver() && $user->caregiver?->id === $caregiver->id;
    }

    public function verify(User $user): bool
    {
        return $user->isAdmin();
    }
}
