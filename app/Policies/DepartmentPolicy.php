<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Department;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view departments');
    }
    public function view(User $user, Department $department)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view departments');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create departments');
    }


    public function update(User $user, Department $department)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit departments');
    }


    public function delete(User $user, Department $department)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete departments');
    }
}
