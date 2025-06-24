<?php

namespace App\Policies;

use App\Models\Court;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CourtPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin', 'court_owner']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Court $court): bool
    {
        // Admins pueden ver cualquier cancha
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        // Propietarios pueden ver sus propias canchas
        if ($user->hasRole('court_owner') && $court->user_id === $user->id) {
            return true;
        }

        // Clientes pueden ver canchas activas
        if ($user->hasRole('client') && $court->status === 'active') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin', 'court_owner']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Court $court): bool
    {
        // Admins pueden editar cualquier cancha
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        // Propietarios pueden editar sus propias canchas
        if ($user->hasRole('court_owner') && $court->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Court $court): bool
    {
        // Admins pueden eliminar cualquier cancha
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        // Propietarios pueden eliminar sus propias canchas
        if ($user->hasRole('court_owner') && $court->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Court $court): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Court $court): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin']);
    }

    /**
     * Determine whether the user can manage court status.
     */
    public function manageStatus(User $user, Court $court): bool
    {
        return $user->hasAnyRole(['admin', 'super_admin']);
    }

    /**
     * Determine whether the user can view court reservations.
     */
    public function viewReservations(User $user, Court $court): bool
    {
        // Admins pueden ver reservas de cualquier cancha
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        // Propietarios pueden ver reservas de sus canchas
        if ($user->hasRole('court_owner') && $court->user_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can manage court reservations.
     */
    public function manageReservations(User $user, Court $court): bool
    {
        // Admins pueden gestionar reservas de cualquier cancha
        if ($user->hasAnyRole(['admin', 'super_admin'])) {
            return true;
        }

        // Propietarios pueden gestionar reservas de sus canchas
        if ($user->hasRole('court_owner') && $court->user_id === $user->id) {
            return true;
        }

        return false;
    }
}
