<?php
// app/Policies/CourtPolicy.php
namespace App\Policies;

use App\Models\Court;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourtPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, Court $court)
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Court $court)
    {
        return $user->isAdmin();
    }
}
