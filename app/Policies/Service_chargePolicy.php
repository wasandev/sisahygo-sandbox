<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Service_charge;
use Illuminate\Auth\Access\HandlesAuthorization;

class Service_chargePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view service_charges');
    }
    public function view(User $user, Service_charge $service_charge)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view service_charges');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create service_charges');
    }


    public function update(User $user, Service_charge $service_charge)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit service_charges');
    }


    public function delete(User $user, Service_charge $service_charge)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete service_charges');
    }
}
