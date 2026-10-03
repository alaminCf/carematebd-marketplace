<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isClient() && $user->client?->id === $booking->client_id) {
            return true;
        }

        if ($user->isCaregiver() && $user->caregiver?->id === $booking->caregiver_id) {
            return true;
        }

        return false;
    }

    public function complete(User $user, Booking $booking): bool
    {
        return $user->isAdmin();
    }

    public function review(User $user, Booking $booking): bool
    {
        return $user->isClient()
            && $user->client?->id === $booking->client_id
            && $booking->canBeReviewedBy($user->client);
    }
}
