<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view products');
    }

    public function view(User $user, Product $product)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view products');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create products');
    }


    public function update(User $user, Product $product)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit products');
    }


    public function delete(User $user, Product $product)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete products');
    }
}
