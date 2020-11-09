<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Driving_license_type;
use Illuminate\Auth\Access\HandlesAuthorization;

class Driving_license_typePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */


    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view driving_license_types');
    }

    public function view(User $user, Driving_license_type $driving_license_type)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view driving_license_types');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create driving_license_types');
    }


    public function update(User $user, Driving_license_type $driving_license_type)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit driving_license_types');
    }


    public function delete(User $user, Driving_license_type $driving_license_type)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete driving_license_types');
    }
}
