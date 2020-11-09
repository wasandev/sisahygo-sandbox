<?php

namespace App\Policies;

use App\Models\Branch_route_cost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Branch_route_costPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view branche_route_costs');
    }
    public function view(User $user, Branch_route_cost $branch_route_cost)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view branche_route_costs');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create branche_route_costs');
    }


    public function update(User $user, Branch_route_cost $branch_route_cost)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit branche_route_costs');
    }


    public function delete(User $user, Branch_route_cost $branch_route_cost)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete branche_route_costs');
    }
}
