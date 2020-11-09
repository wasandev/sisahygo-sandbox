<?php

namespace App\Policies;

use App\Models\Branch_route;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Branch_routePolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view branch_routes');
    }
    public function view(User $user, Branch_route $branch_route)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view branch_routes');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create branch_routes');
    }


    public function update(User $user, Branch_route $branch_route)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit branch_routes');
    }


    public function delete(User $user, Branch_route $branch_route)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete branch_routes');
    }
}
