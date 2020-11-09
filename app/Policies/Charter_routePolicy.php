<?php

namespace App\Policies;

use App\Models\Charter_route;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Charter_routePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view charter_routes');
    }
    public function view(User $user, Charter_route $charter_route)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view charter_routes');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create charter_routes');
    }


    public function update(User $user, Charter_route $charter_route)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit charter_routes');
    }


    public function delete(User $user, Charter_route $charter_route)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete charter_routes');
    }
}
