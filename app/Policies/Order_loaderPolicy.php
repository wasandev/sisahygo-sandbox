<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_loader;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_loaderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view order_loaders') || $user->hasPermissionTo('view own order_loaders');
    }
    public function view(User $user, Order_loader $order_loader)
    {
        if ($user->hasPermissionTo('view own order_loaders')) {
            return $user->role == 'admin' || ($user->id === $order_loader->user_id || $user->id === $order_loader->checker_id);
        }

        return $user->role == 'admin' || $user->hasPermissionTo('view order_loaders');
    }

    public function create(User $user)
    {
        return false;
    }


    public function update(User $user, Order_loader $order_loader)
    {


        return ($user->hasPermissionTo('manage order_loaders') && ($order_loader->order_status == "loaded" || $order_loader->order_status == "confirmed"));
    }

    public function delete(User $user, Order_loader $order_loader)
    {
        return false;
    }

    public function addOrder_detail(User $user, Order_loader $order_loader)
    {

        return false;
    }
}
