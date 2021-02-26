<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Bankaccount;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankaccountPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view bankaccounts');
    }

    /**
     * Determine whether the user can view the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bankaccount  $Bankaccount
     * @return mixed
     */
    public function view(User $user, Bankaccount $Bankaccount)
    {


        return $user->role == 'admin' || $user->hasPermissionTo('view bankaccounts');
    }

    /**
     * Determine whether the user can create company profiles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create bankaccounts');
    }

    /**
     * Determine whether the user can update the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bankaccount  $Bankaccount
     * @return mixed
     */
    public function update(User $user, Bankaccount $Bankaccount)
    {
        if ($user->branch->type == 'partner') {
            return $user->role == 'admin' || $user->hasPermissionTo('edit bankaccounts') ||
                $user->branch_id == $Bankaccount->branch_id;
        }
        return $user->role == 'admin' || $user->hasPermissionTo('edit bankaccounts');
    }

    /**
     * Determine whether the user can delete the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bankaccount  $Bankaccount
     * @return mixed
     */
    public function delete(User $user, Bankaccount $Bankaccount)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete bankaccounts');
    }
}
