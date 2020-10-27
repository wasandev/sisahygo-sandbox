<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Car;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
{
    use HandlesAuthorization;


    public function view(User $user, Car $car)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view cars');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create cars');
    }


    public function update(User $user, Car $car)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit cars');
    }


    public function delete(User $user, Car $car)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete cars');
    }
}
