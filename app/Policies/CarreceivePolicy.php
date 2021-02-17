<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Carreceive;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarreceivePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view car_receives');
    }
    public function view(User $user, Carreceive $carpreceive)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view car_receives');
    }
    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create car_receives');
    }
    public function update(User $user, Carreceive $car_carreceive)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('edit car_receives');
    }


    public function delete(User $user, Carreceive $car_carreceive)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete car_receives');
    }
}
