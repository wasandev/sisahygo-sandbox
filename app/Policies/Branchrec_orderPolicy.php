<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Branchrec_order;
use Illuminate\Auth\Access\HandlesAuthorization;

class Branchrec_orderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view branchrec_orders');
    }
    public function view(User $user, Branchrec_order $branchrec_order)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view branchrec_orders');
    }

    public function create(User $user)
    {
        return false;
    }


    public function update(User $user, Branchrec_order $branchrec_order)
    {

        return ($user->role == 'admin');
    }

    public function delete(User $user, Branchrec_order $branchrec_order)
    {
        return false;
    }

    public function addOrder_detail(User $user, Branchrec_order $branchrec_order)
    {

        return false;
    }
}
