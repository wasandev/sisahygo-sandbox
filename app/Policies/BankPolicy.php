<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Bank;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view banks');
    }

    /**
     * Determine whether the user can view the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bank  $Bank
     * @return mixed
     */
    public function view(User $user, Bank $Bank)
    {


        return $user->role == 'admin' || $user->hasPermissionTo('view banks');
    }

    /**
     * Determine whether the user can create company profiles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create banks');
    }

    /**
     * Determine whether the user can update the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bank  $Bank
     * @return mixed
     */
    public function update(User $user, Bank $Bank)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit banks');
    }

    /**
     * Determine whether the user can delete the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bank  $Bank
     * @return mixed
     */
    public function delete(User $user, Bank $Bank)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete banks');
    }
}
