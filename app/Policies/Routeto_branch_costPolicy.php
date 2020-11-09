<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Routeto_branch_cost;
use Illuminate\Auth\Access\HandlesAuthorization;

class Routeto_branch_costPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view routeto_branch_costs');
    }

    public function view(User $user, Routeto_branch_cost $routeto_branch_cost)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view routeto_branch_costs');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create routeto_branch_costs');
    }


    public function update(User $user, Routeto_branch_cost $routeto_branch_cost)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit routeto_branch_costs');
    }


    public function delete(User $user, Routeto_branch_cost $routeto_branch_cost)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete routeto_branch_costs');
    }
}
