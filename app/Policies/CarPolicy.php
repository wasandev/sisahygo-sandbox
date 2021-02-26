<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Car;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view cars');
    }
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
        if ($user->branch->type == 'partner') {
            return $user->role == 'admin' || $user->hasPermissionTo('edit edit cars') ||
                $car->vendor_id == $user->branch->vendor_id;
        }

        return $user->role == 'admin' || $user->hasPermissionTo('edit cars');
    }


    public function delete(User $user, Car $car)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete cars');
    }
}
