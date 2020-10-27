<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Productservice_price;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductServicePricePolicy
{
    use HandlesAuthorization;


    public function view(User $user, Productservice_price $productservice_price)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view productservice_prices');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create productservice_prices');
    }


    public function update(User $user, Productservice_price $productservice_price)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit productservice_prices');
    }


    public function delete(User $user, Productservice_price $productservice_price)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete productservice_prices');
    }
}
