<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Productservice_price;
use Illuminate\Auth\Access\HandlesAuthorization;

class Productservice_pricePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view productservice_prices');
    }
    public function view(User $user, Productservice_price $productservice_price)
    {
        if ($user->hasPermissionTo('view own productservice_prices')) {
            return $user->id === $productservice_price->user_id;
        }

        return $user->role == 'admin' || $user->hasPermissionTo('view productservice_prices');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasAnyPermission(['manage productservice_prices', 'manage own productservice_prices']);
    }


    public function update(User $user, Productservice_price $productservice_price)
    {
        if ($user->hasPermissionTo('manage own productservice_prices')) {
            return $user->id === $productservice_price->user_id;
        }
        return $user->role == 'admin' || $user->hasPermissionTo('manage productservice_prices');
    }


    public function delete(User $user, Productservice_price $productservice_price)
    {
        if ($user->hasPermissionTo('manage own productservice_prices')) {
            return $user->id === $productservice_price->user_id;
        }
        return $user->role == 'admin' || $user->hasPermissionTo('manage productservice_prices');
    }
}
