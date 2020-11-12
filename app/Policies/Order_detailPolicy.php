<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_detail;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_detailPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view Order_details');
    }
    public function view(User $user, Order_detail $Order_detail)
    {
        if ($user->hasPermissionTo('view own Order_details')) {
            return $user->id === $Order_detail->user_id;
        }

        return $user->role == 'admin' || $user->hasPermissionTo('view Order_details');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasAnyPermission(['manage Order_details', 'manage own Order_details']);
    }


    public function update(User $user, Order_detail $Order_detail)
    {
        if ($user->hasPermissionTo('manage own Order_details')) {
            return $user->id === $Order_detail->user_id;
        }
        return $user->role == 'admin' || $user->hasPermissionTo('manage Order_details');
    }


    public function delete(User $user, Order_detail $Order_detail)
    {
        if ($user->hasPermissionTo('manage own Order_details')) {
            return $user->id === $Order_detail->user_id;
        }
        return $user->role == 'admin' || $user->hasPermissionTo('manage Order_details');
    }
}
