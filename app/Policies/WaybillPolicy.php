<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Waybill;
use Illuminate\Auth\Access\HandlesAuthorization;

class WaybillPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view waybills');
    }
    public function view(User $user, Waybill $waybill)
    {
        // if ($user->hasPermissionTo('view own waybills')) {
        //     return $user->id === $waybill->user_id;
        // }
        return $user->role == 'admin' || $user->hasPermissionTo('view waybills');
    }

    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasAnyPermission(['manage waybills', 'manage own waybills']);
    }

    public function update(User $user, Waybill $waybill)
    {

        return ($user->role == 'admin' || ($user->hasPermissionTo('manage waybills')));
    }

    public function delete(User $user, Waybill $waybill)
    {

        return ($user->role == 'admin' && $waybill->waybill_payable == 0);
    }

    // public function addOrder_header(User $user, Waybill $waybill)
    // {
    //     // if ($waybill->order_status == "new") {
    //     //     return true;
    //     // }
    //     return false;
    // }
}
