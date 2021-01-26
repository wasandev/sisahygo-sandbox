<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Branch_area;
use Illuminate\Auth\Access\HandlesAuthorization;

class Branch_areaPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view branchareas');
    }
    public function view(User $user, Branch_area $branch_area)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view branchareas');
    }

    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create branchareas');
    }

    public function update(User $user, Branch_area $branch_area)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('edit branchareas');
    }

    public function delete(User $user, Branch_area $branch_area)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete branchareas');
    }
}
