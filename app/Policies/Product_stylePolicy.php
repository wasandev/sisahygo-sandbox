<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product_style;
use Illuminate\Auth\Access\HandlesAuthorization;

class Product_stylePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view product_styles');
    }
    public function view(User $user, Product_style $product_style)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view product_styles');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create product_styles');
    }


    public function update(User $user, Product_style $product_style)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit product_styles');
    }


    public function delete(User $user, Product_style $product_style)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete product_styles');
    }
}
