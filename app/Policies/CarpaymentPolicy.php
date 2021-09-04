<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Carpayment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarpaymentPolicy
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
        return $user->role == 'admin' || $user->hasPermissionTo('view car_payments');
    }
    public function view(User $user, Carpayment $carpayment)
    {
        return $user->hasPermissionTo('view car_payments');
    }
    public function create(User $user)
    {
        return $user->hasPermissionTo('create car_payments');
    }
    public function update(User $user, Carpayment $car_carpayment)
    {
        return $user->hasPermissionTo('edit car_payments');
    }


    public function delete(User $user, Carpayment $car_carpayment)
    {
        return $user->hasPermissionTo('delete car_payments');
    }
}
