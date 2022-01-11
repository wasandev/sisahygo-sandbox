<?php

namespace App\Policies;

use App\Models\Branch_balance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Branch_balancePolicy
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
        return $user->role == 'admin' || $user->hasPermissionTo('view branch_balance');
    }
    public function view(User $user, Branch_balance $branch_balance)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view branch_balance');
    }

    public function create(User $user)
    {
        return false;
        //$user->role == 'admin' || $user->hasPermissionTo('create branch_balance');
    }

    public function update(User $user, Branch_balance $branch_balance)
    {
        return         $user->role == 'admin' || $user->hasPermissionTo('edit branch_balance');
    }

    public function delete(User $user, Branch_balance $branch_balance)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete branch_balance');
    }
}
