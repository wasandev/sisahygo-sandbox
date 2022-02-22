<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order_rec;
use Illuminate\Auth\Access\HandlesAuthorization;

class Order_recPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view order_headers') || $user->hasPermissionTo('view own order_headers');
    }
    public function view(User $user, Order_rec $Order_rec)
    {

        return ($user->role == 'admin' || $user->hasPermissionTo('view order_headers'));
    }

    public function create(User $user)
    {
        return false;
    }


    public function update(User $user, Order_rec $Order_rec)
    {
        return false;
    }

    public function delete(User $user, Order_rec $Order_rec)
    {
        return false;
    }

    public function addOrder_detail(User $user, Order_rec $Order_rec)
    {
        return false;
    }
}
