<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_branch;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_branchPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view order_branch');
    }

    /**
     * Determine whether the user can view the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order_branch $Order_banktransfer
     * @return mixed
     */
    public function view(User $user, Order_branch $order_branch)
    {


        return $user->role == 'admin' || $user->hasPermissionTo('view order_branch');
    }

    /**
     * Determine whether the user can create company profiles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order_branch  $Order_banktransfer
     * @return mixed
     */
    public function update(User $user, Order_branch $order_branch)
    {
        return false;
        //return $user->role == 'admin' || $user->hasPermissionTo('edit order_cash');
    }

    /**
     * Determine whether the user can delete the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order_banktransfer  $Order_banktransfer
     * @return mixed
     */
    public function delete(User $user, Order_branch $order_branch)
    {
        return false;
    }

    public function addOrder_detail(User $user, Order_branch $order_branch)
    {

        return false;
    }
}
