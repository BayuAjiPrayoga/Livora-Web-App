<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * BookingPolicy handles authorization for booking-related actions
 * 
 * This policy centralizes all authorization logic for bookings,
 * making it easier to maintain and test access control.
 */
class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any bookings
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view their own bookings
        return true;
    }

    /**
     * Determine if the user can view the booking
     *
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function view(User $user, Booking $booking): bool
    {
        // Admin can view all bookings
        if ($user->role === 'admin') {
            return true;
        }

        // Mitra can view bookings for their properties
        if ($user->role === 'mitra') {
            return $booking->room->boardingHouse->user_id === $user->id;
        }

        // Tenants can only view their own bookings
        return $booking->user_id === $user->id;
    }

    /**
     * Determine if the user can create bookings
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Only tenants and admin can create bookings
        return in_array($user->role, ['tenant', 'admin']);
    }

    /**
     * Determine if the user can update the booking
     *
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function update(User $user, Booking $booking): bool
    {
        // Admin can update any booking
        if ($user->role === 'admin') {
            return true;
        }

        // Tenants can only update their own pending bookings
        if ($user->role === 'tenant') {
            return $booking->user_id === $user->id 
                && $booking->status === Booking::STATUS_PENDING;
        }

        return false;
    }

    /**
     * Determine if the user can delete/cancel the booking
     *
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function delete(User $user, Booking $booking): bool
    {
        // Admin can cancel any booking
        if ($user->role === 'admin') {
            return true;
        }

        // Tenants can only cancel their own pending or confirmed bookings
        if ($user->role === 'tenant') {
            return $booking->user_id === $user->id 
                && in_array($booking->status, [
                    Booking::STATUS_PENDING, 
                    Booking::STATUS_CONFIRMED
                ]);
        }

        return false;
    }

    /**
     * Determine if the user can confirm the booking
     *
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function confirm(User $user, Booking $booking): bool
    {
        // Only admin and mitra (property owner) can confirm bookings
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'mitra') {
            return $booking->room->boardingHouse->user_id === $user->id
                && $booking->status === Booking::STATUS_PENDING;
        }

        return false;
    }

    /**
     * Determine if the user can mark booking as active
     *
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function activate(User $user, Booking $booking): bool
    {
        // Only admin and mitra can activate confirmed bookings
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'mitra') {
            return $booking->room->boardingHouse->user_id === $user->id
                && $booking->status === Booking::STATUS_CONFIRMED;
        }

        return false;
    }

    /**
     * Determine if the user can complete the booking
     *
     * @param User $user
     * @param Booking $booking
     * @return bool
     */
    public function complete(User $user, Booking $booking): bool
    {
        // Only admin and mitra can complete active bookings
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'mitra') {
            return $booking->room->boardingHouse->user_id === $user->id
                && $booking->status === Booking::STATUS_ACTIVE;
        }

        return false;
    }
}
