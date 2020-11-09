<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer_product_price;
use Illuminate\Auth\Access\HandlesAuthorization;

class Customer_product_pricePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view customerproduct_prices');
    }
    public function view(User $user, Customer_product_price $customer_product_price)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view customerproduct_prices');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create customerproduct_prices');
    }


    public function update(User $user, Customer_product_price $customer_product_price)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit customerproduct_prices');
    }


    public function delete(User $user, Customer_product_price $customer_product_price)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete customerproduct_prices');
    }
}
