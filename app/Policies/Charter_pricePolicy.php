<?php

namespace App\Policies;

use App\Models\Charter_price;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Charter_pricePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view charter_prices');
    }
    public function view(User $user, Charter_price $charter_price)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view charter_prices');
    }


    public function create(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('create charter_prices');
    }


    public function update(User $user, Charter_price $charter_price)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit charter_prices');
    }


    public function delete(User $user, Charter_price $charter_price)
    {
        return $user->role == 'admin' ||  $user->hasPermissionTo('delete charter_prices');
    }
}
