<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_problem;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_problemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view order_problems') || $user->hasPermissionTo('view own order_problems');
    }
    public function view(User $user, Order_problem $order_problem)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view order_problems');
    }

    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create order_problems');
    }


    public function update(User $user, Order_problem $order_problem)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('edit order_problems');
    }

    public function delete(User $user, Order_problem $order_problem)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete order_problems');
    }

    public function addOrder_detail(User $user, Order_problem $order_problem)
    {

        return false;
    }
}
