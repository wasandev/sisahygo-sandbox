<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_detail;
//use App\Models\Order_header;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_detailPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {


        return $user->role == 'admin' || $user->hasPermissionTo('view order_details') || $user->hasPermissionTo('view own order_details');
    }
    public function view(User $user, Order_detail $Order_detail)
    {
        if ($user->hasPermissionTo('view own order_details')) {
            return $user->id === $Order_detail->user_id;
        }

        return $user->role == 'admin' || $user->hasPermissionTo('view order_details');
    }


    public function create(User $user)
    {

        return ($user->role == 'admin' || $user->hasAnyPermission(['manage order_details', 'manage own order_details']));
    }


    public function update(User $user, Order_detail $Order_detail)
    {
        // if ($user->hasAnyPermission('manage order_details', 'manage own order_details')) {
        //     return ($user->id === $Order_detail->user_id) ||  ($Order_detail->order_header->order_status == "checking" || $Order_detail->order_header->order_status == "new");
        // }
        return ($user->role == 'admin' || ($user->hasPermissionTo('manage order_details')
            && ($Order_detail->order_header->order_status == "checking" || $Order_detail->order_header->order_status == "new")));
    }


    public function delete(User $user, Order_detail $Order_detail)
    {
        // if ($user->hasAnyPermission('manage order_details', 'manage own order_details')) {
        //     return ($user->id === $Order_detail->user_id) ||  ($Order_detail->order_header->order_status == "checking" || $Order_detail->order_header->order_status == "new");
        // }
        return ($user->role == 'admin' || ($user->hasPermissionTo('manage order_details')
            && ($Order_detail->order_header->order_status == "checking" || $Order_detail->order_header->order_status == "new")));
    }
}
