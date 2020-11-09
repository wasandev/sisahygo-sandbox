<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Serviceprice;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicepricePolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view serviceprices');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create serviceprices');
    }


    public function update(User $user, Serviceprice $serviceprice)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit serviceprices');
    }


    public function delete(User $user, Serviceprice $serviceprice)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete serviceprices');
    }
}
