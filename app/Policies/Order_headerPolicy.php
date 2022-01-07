<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_header;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_headerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view order_headers') || $user->hasPermissionTo('view own order_headers');
    }
    public function view(User $user, Order_header $Order_header)
    {
        if ($user->hasPermissionTo('view own order_headers')) {
            return $user->role == 'admin' || $user->id === $Order_header->user_id;
        }
        return ($user->role == 'admin' || $user->hasPermissionTo('view order_headers'));
    }

    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasAnyPermission(['manage order_headers', 'manage own order_headers']);
    }


    public function update(User $user, Order_header $Order_header)
    {
        if ($user->hasPermissionTo('manage own order_headers')) {
            return ($user->role == 'admin') || ($user->id === $Order_header->user_id) && ($Order_header->order_status == "new");
        }
        return ($user->role == 'admin' || $user->hasPermissionTo('manage order_headers')) && ($Order_header->order_status == "new");
    }

    public function delete(User $user, Order_header $Order_header)
    {
        if ($user->hasPermissionTo('manage order_headers')) {
            return $Order_header->order_status == "new";
        }
        //return ($user->role == 'admin');
    }

    public function addOrder_detail(User $user, Order_header $Order_header)
    {
        if ($Order_header->order_status == "new") {
            return true;
        }
        return false;
    }
}
