<?php

namespace App\Policies;

use App\Models\Branch_balance_item;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Branch_balance_itemPolicy
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
        return true;
    }
    public function view(User $user, Branch_balance_item $branch_balance_item)
    {
        return  true;
    }

    public function create(User $user)
    {
        return false;
        //$user->role == 'admin' || $user->hasPermissionTo('create branch_balance');
    }

    public function update(User $user, Branch_balance_item $branch_balance_item)
    {
        return false;
        //$user->role == 'admin' || $user->hasPermissionTo('edit branch_balance');
    }

    public function delete(User $user, Branch_balance_item $branch_balance_item)
    {
        return false;
        //$user->role == 'admin' || $user->hasPermissionTo('delete branch_balance');
    }
}
