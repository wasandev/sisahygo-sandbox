<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Branch_area;
use Illuminate\Auth\Access\HandlesAuthorization;

class BranchAreaPolicy
{
    use HandlesAuthorization;


    public function view(User $user, Branch_area $branch_area)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view branch_areas');
    }

    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create branch_areas');
    }

    public function update(User $user, Branch_area $branch)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('edit branch_areas');
    }

    public function delete(User $user, Branch_area $branch)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete branch_areas');
    }
}
