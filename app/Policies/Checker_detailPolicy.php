<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Checker_detail;

use Illuminate\Auth\Access\HandlesAuthorization;

class Checker_detailPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {


        return $user->role == 'admin' || $user->hasPermissionTo('view checker_details') || $user->hasPermissionTo('view own checker_details');
    }
    public function view(User $user, Checker_detail $checker_detail)
    {
        if ($user->hasPermissionTo('view own checker_details')) {
            return $user->id === $checker_detail->user_id;
        }

        return $user->role == 'admin' || $user->hasPermissionTo('view checker_details');
    }


    public function create(User $user)
    {

        return ($user->role == 'admin' || $user->hasAnyPermission(['manage checker_details', 'manage own checker_details']));
    }


    public function update(User $user, Checker_detail $checker_detail)
    {
        // if ($user->hasAnyPermission('manage checker_details', 'manage own checker_details')) {
        //     return ($user->id === $checker_detail->user_id) ||  ($checker_detail->order_checker->order_status == "checking");
        // }
        return ($user->role == 'admin' || ($user->hasPermissionTo('manage checker_details')
            && $checker_detail->order_checker->order_status == "checking"));
    }


    public function delete(User $user, Checker_detail $checker_detail)
    {
        // if ($user->hasAnyPermission('manage checker_details', 'manage own checker_details')) {
        //     return ($user->id === $checker_detail->user_id) ||  ($checker_detail->order_checker->order_status == "checking");
        // }
        return ($user->role == 'admin' || ($user->hasPermissionTo('manage checker_details')
            && $checker_detail->order_checker->order_status == "checking"));
    }
}
