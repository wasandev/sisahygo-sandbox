<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Car_balance;
use Illuminate\Auth\Access\HandlesAuthorization;

class Car_balancePolicy
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
        return $user->role == 'admin' || $user->hasPermissionTo('view car_balances');
    }
    public function view(User $user, Car_balance $car_balance)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view car_balances');
    }
    public function create(User $user)
    {
        return false;
    }
    public function update(User $user, Car_balance $car_balance)
    {

        return $user->role == 'admin';
    }


    public function delete(User $user, Car_balance $car_balance)
    {
        return $user->role == 'admin';
    }
}
