<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Position;
use Illuminate\Auth\Access\HandlesAuthorization;

class PositionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view positions');
    }

    public function view(User $user, Position $position)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view positions');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create positions');
    }


    public function update(User $user, Position $position)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit positions');
    }


    public function delete(User $user, Position $position)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete positions');
    }
}
