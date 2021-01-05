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
        return $user->role == 'admin' || $user->hasPermissionTo('view productstyles');
    }
    public function view(User $user, Product_style $product_style)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view productstyles');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create productstyles');
    }


    public function update(User $user, Product_style $product_style)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit productstyles');
    }


    public function delete(User $user, Product_style $product_style)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete productstyles');
    }
}
