<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnitPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view units');
    }
    public function view(User $user, Unit $unit)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view units');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create units');
    }


    public function update(User $user, Unit $unit)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit units');
    }


    public function delete(User $user, Unit $unit)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete units');
    }
}
