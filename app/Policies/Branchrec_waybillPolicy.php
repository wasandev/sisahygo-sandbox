<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Branchrec_waybill;
use Illuminate\Auth\Access\HandlesAuthorization;

class Branchrec_waybillPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view branchrec_waybills');
    }
    public function view(User $user, Branchrec_waybill $branchrec_waybill)
    {
        // if ($user->hasPermissionTo('view own branchrec_waybills')) {
        //     return $user->id === $branchrec_waybill->user_id;
        // }
        return $user->role == 'admin' || $user->hasPermissionTo('view branchrec_waybills');
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Branchrec_waybill $branchrec_waybill)
    {
        return ($user->role == 'admin' || $user->hasPermissionTo('manage branchrec_waybills'))
            && ($branchrec_waybill->waybill_status == 'arrival');
    }

    public function delete(User $user, Branchrec_waybill $branchrec_waybill)
    {
        return false;
    }
}
