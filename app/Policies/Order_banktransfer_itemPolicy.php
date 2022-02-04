<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_banktransfer_item;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_banktransfer_itemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order_banktransfer  $Order_banktransfer
     * @return mixed
     */
    public function view(User $user, Order_banktransfer_item $order_banktransfer_item)
    {

        return true;
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
    public function update(User $user, Order_banktransfer_item $order_banktransfer_item)
    {

        return $user->role == 'admin';
    }

    /**
     * Determine whether the user can delete the company profile.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order_banktransfer  $Order_banktransfer
     * @return mixed
     */
    public function delete(User $user, Order_banktransfer_item $order_banktransfer_item)
    {
        return $user->role == 'admin';
    }
}
