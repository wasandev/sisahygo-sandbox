<?php

namespace App\Policies;

use App\Models\Vendor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view vendors');
    }
    public function view(User $user, Vendor $vendor)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view vendors');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create vendors');
    }


    public function update(User $user, Vendor $vendor)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit vendors');
    }


    public function delete(User $user, Vendor $vendor)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete vendors');
    }
}
