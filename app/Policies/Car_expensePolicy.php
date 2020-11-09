<?php

namespace App\Policies;

use App\Models\Car_expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Car_expensePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view car_expenses');
    }
    public function view(User $user, Car_expense $car_expense)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view car_expenses');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create car_expenses');
    }


    public function update(User $user, Car_expense $car_expense)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit car_expenses');
    }


    public function delete(User $user, Car_expense $car_expense)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete car_expenses');
    }
}
