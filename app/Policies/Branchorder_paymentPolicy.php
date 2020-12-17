<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Branchorder_payment;
use Illuminate\Auth\Access\HandlesAuthorization;

class Branchorder_paymentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view branchorder_payments') || $user->hasPermissionTo('view own branchorder_payments');
    }
    public function view(User $user, Branchorder_payment $branchorder_payment)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view branchorder_payments');
    }

    public function create(User $user)
    {
        return false;
    }


    public function update(User $user, Branchorder_payment $branchorder_payment)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('edit branchorder_payments');
    }

    public function delete(User $user, Branchorder_payment $branchorder_payment)
    {
        return false;
    }

    public function addOrder_detail(User $user, Branchorder_payment $branchorder_payment)
    {

        return false;
    }
}
