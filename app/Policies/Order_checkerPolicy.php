<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_checker;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_checkerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view order_checkers') || $user->hasPermissionTo('view own order_checkers');
    }
    public function view(User $user, Order_checker $order_checker)
    {
        if ($user->hasPermissionTo('view own order_checkers')) {
            return $user->role == 'admin' || ($user->id === $order_checker->user_id || $user->id === $order_checker->checker_id);
        }

        return $user->role == 'admin' || $user->hasPermissionTo('view order_checkers');
    }

    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasAnyPermission(['manage order_checkers', 'manage own order_checkers']);
    }


    public function update(User $user, Order_checker $order_checker)
    {
        if ($user->hasPermissionTo('manage own order_checkers')) {
            return $user->id === $order_checker->user_id && $order_checker->order_status == "checking";
        }
        return ($user->role == 'admin' || $user->hasPermissionTo('manage order_checkers')) && $order_checker->order_status == "checking";
    }

    public function delete(User $user, Order_checker $order_checker)
    {
        if ($user->hasPermissionTo('manage own order_checkers')) {
            return ($user->id === $order_checker->user_id) && $order_checker->order_status == "checking";
        }
        return ($user->role == 'admin' || $user->hasPermissionTo('manage order_checkers')) && $order_checker->order_status == "checking";
    }

    public function addChecker_detail(User $user, Order_checker $order_checker)
    {
        if ($order_checker->order_status == "checking") {
            return true;
        }
        return false;
    }
}
