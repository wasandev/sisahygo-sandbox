<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Businesstype;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinesstypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view businesstypes');
    }

    public function view(User $user, Businesstype $businesstype)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view businesstypes');
    }

    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create businesstypes');
    }


    public function update(User $user, Businesstype $businesstype)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit businesstypes');
    }


    public function delete(User $user, Businesstype $businesstype)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete businesstypes');
    }
}
