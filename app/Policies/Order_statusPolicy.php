<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_status;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_statusPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function view(User $user, Order_status $order_status)
    {
        return true;
    }


    public function create(User $user)
    {
        return false;
    }


    public function update(User $user, Order_status $order_status)
    {
        return false;
    }


    public function delete(User $user, Order_status $order_status)
    {
        return false;
    }
}
