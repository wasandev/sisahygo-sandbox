<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cartype;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartypePolicy
{
    use HandlesAuthorization;
    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view cartypes');
    }


    public function view(User $user, Cartype $cartype)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view cartypes');
    }


    public function create(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('create cartypes');
    }


    public function update(User $user, Cartype $cartype)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit cartypes');
    }


    public function delete(User $user, Cartype $cartype)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete cartypes');
    }
}
