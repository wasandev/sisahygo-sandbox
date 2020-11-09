<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Routeto_branch;
use Illuminate\Auth\Access\HandlesAuthorization;

class Routeto_branchPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view routeto_branches');
    }


    public function view(User $user, Routeto_branch $routeto_branch)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view routeto_branches');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create routeto_branches');
    }


    public function update(User $user, Routeto_branch $routeto_branch)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit routeto_branches');
    }


    public function delete(User $user, Routeto_branch $routeto_branch)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete routeto_branches');
    }
}
