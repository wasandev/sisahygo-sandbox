<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ar_balance;
use Illuminate\Auth\Access\HandlesAuthorization;

class Ar_balancePolicy
{
    use HandlesAuthorization;
    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view ar_balance');
    }
    public function view(User $user, Ar_balance $ar_balance)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view ar_balance');
    }


    public function create(User $user)
    {
        return false;
        //return $user->role == 'admin' || $user->hasPermissionTo('create ar_balance');
    }


    public function update(User $user, Ar_balance $ar_balance)
    {

        return false;
        //return $user->role == 'admin' || $user->hasPermissionTo('edit ar_balance');
    }


    public function delete(User $user, Ar_balance $ar_balance)
    {
        //return $user->role == 'admin' || $user->hasPermissionTo('delete ar_customer');
        return false;
    }
}
