<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Waybill_charter;
use Illuminate\Auth\Access\HandlesAuthorization;

class Waybill_charterPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view waybill_charters');
    }

    public function view(User $user, Waybill_charter $waybill_charter)
    {
        return $user->hasPermissionTo('view waybill_charters');
    }


    public function create(User $user)
    {
        return false;
    }


    public function update(User $user, Waybill_charter $waybill_charter)
    {
        return  $user->role == 'admin';
    }


    public function delete(User $user, Waybill_charter $waybill_charter)
    {
        return false;
    }

    // public function addOrder_charter(User $user, Waybill_charter $waybill_charter)
    // {

    //     return false;
    // }
}
