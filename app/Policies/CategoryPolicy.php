<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view categories');
    }
    public function view(User $user, Category $category)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view categories');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create categories');
    }


    public function update(User $user, Category $category)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit categories');
    }


    public function delete(User $user, Category $category)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete categories');
    }
}
