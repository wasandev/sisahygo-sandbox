<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_dropship;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_dropshipPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return   $user->hasPermissionTo('view order_dropships');
    }
    public function view(User $user, Order_dropship $order_dropship)
    {
        return $user->hasPermissionTo('view order_dropships');
    }

    public function create(User $user)
    {
        return  false;
    }


    public function update(User $user, Order_dropship $order_dropship)
    {
        return false;
    }

    public function delete(User $user, Order_dropship $order_dropship)
    {
        return false;
    }

    public function addOrder_detail(User $user, Order_dropship $order_dropship)
    {

        return false;
    }
}
