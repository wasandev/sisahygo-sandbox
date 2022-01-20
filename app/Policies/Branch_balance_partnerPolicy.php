<?php

namespace App\Policies;

use App\Models\Branch_balance_partner;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Branch_balance_partnerPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view branch_balance_partner');
    }
    public function view(User $user, Branch_balance_partner $branch_balance_partner)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view branch_balance_partner');
    }

    public function create(User $user)
    {
        return false;
        //$user->role == 'admin' || $user->hasPermissionTo('create branch_balance_partner');
    }

    public function update(User $user, Branch_balance_partner $branch_balance_partner)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('edit branch_balance_partner');
    }

    public function delete(User $user, Branch_balance_partner $branch_balance_partner)
    {
        return   $user->role == 'admin';
    }
}
