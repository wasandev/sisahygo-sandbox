<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Waybill_status;
use Illuminate\Auth\Access\HandlesAuthorization;

class Waybill_statusPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function view(User $user, Waybill_status $waybill_status)
    {
        return true;
    }


    public function create(User $user)
    {
        return false;
    }


    public function update(User $user, Waybill_status $waybill_status)
    {
        return false;
    }


    public function delete(User $user, Waybill_status $waybill_status)
    {
        return false;
    }
}
