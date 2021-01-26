<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ar_customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class Ar_customerPolicy
{
    use HandlesAuthorization;
    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view ar_customer');
    }
    public function view(User $user, Ar_customer $ar_customer)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view ar_customer');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create ar_customer');
    }


    public function update(User $user, Ar_customer $ar_customer)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit ar_customer');
    }


    public function delete(User $user, Ar_customer $ar_customer)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete ar_customer');
    }
}
