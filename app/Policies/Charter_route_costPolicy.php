<?php

namespace App\Policies;

use App\Models\Charter_route_cost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Charter_route_costPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' ||  $user->hasPermissionTo('view charter_route_costs');
    }
    public function view(User $user, Charter_route_cost $charter_route_cost)
    {
        return $user->role == 'admin' ||  $user->hasPermissionTo('view charter_route_costs');
    }


    public function create(User $user)
    {
        return  $user->role == 'admin' ||  $user->hasPermissionTo('create charter_route_costs');
    }


    public function update(User $user, Charter_route_cost $charter_route_cost)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit charter_route_costs');
    }


    public function delete(User $user, Charter_route_cost $charter_route_cost)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete charter_route_costs');
    }
}
