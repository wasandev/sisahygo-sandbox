<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_charter;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_charterPolicy
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
        return $user->hasPermissionTo('view order_charters');
    }

    public function view(User $user, Order_charter $order_charter)
    {
        return $user->hasPermissionTo('view order_charters');
    }


    public function create(User $user)
    {
        return false;
    }


    public function update(User $user, Order_charter $order_charter)
    {
        return false;
    }


    public function delete(User $user, Order_charter $order_charter)
    {
        return false;
    }

    public function addOrder_detail(User $user, Order_charter $order_charter)
    {

        return false;
    }
}
