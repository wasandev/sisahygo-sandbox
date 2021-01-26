<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_cash;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_cashPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view order_cash');
    }

    /**
     * Determine whether the user can view the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order_banktransfer  $Order_banktransfer
     * @return mixed
     */
    public function view(User $user, Order_cash $order_cash)
    {


        return $user->role == 'admin' || $user->hasPermissionTo('view order_cash');
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
     * @param  \App\Models\Order_banktransfer  $Order_banktransfer
     * @return mixed
     */
    public function update(User $user, Order_cash $order_cash)
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
    public function delete(User $user, Order_cash $order_cash)
    {
        return false;
    }

    public function addOrder_detail(User $user, Order_cash $order_cash)
    {

        return false;
    }
}
