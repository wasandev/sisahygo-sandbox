<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Serviceprice;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePricePolicy
{
    use HandlesAuthorization;


    public function view(User $user, Serviceprice $serviceprice)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view service_prices');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create service_prices');
    }


    public function update(User $user, Serviceprice $serviceprice)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit service_prices');
    }


    public function delete(User $user, Serviceprice $serviceprice)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete service_prices');
    }
}
