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
        return  $user->role == 'admin' || $user->hasPermissionTo('view Order_headers');
    }
    public function view(User $user, Order_header $Order_header)
    {
        if ($user->hasPermissionTo('view own Order_headers')) {
            return $user->id === $Order_header->user_id;
        }

        return $user->role == 'admin' || $user->hasPermissionTo('view Order_headers');
    }

    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasAnyPermission(['manage Order_headers', 'manage own Order_headers']);
    }


    public function update(User $user, Order_header $Order_header)
    {
        if ($user->hasPermissionTo('manage own Order_headers')) {
            return ($user->id === $Order_header->user_id) && ($Order_header->order_status == "new");
        }
        return ($user->role == 'admin' || $user->hasPermissionTo('manage Order_headers')) && ($Order_header->order_status == "new");
    }

    public function delete(User $user, Order_header $Order_header)
    {
        if ($user->hasPermissionTo('manage own Order_headers')) {
            return ($user->id === $Order_header->user_id) && ($Order_header->order_status == "new");
        }
        return ($user->role == 'admin' || $user->hasPermissionTo('manage Order_headers')) && ($Order_header->order_status == "new");
    }

    public function addOrder_detail(User $user, ORder_header $Order_header)
    {
        if ($Order_header->order_status == "new") {
            return true;
        }
        return false;
    }
}
