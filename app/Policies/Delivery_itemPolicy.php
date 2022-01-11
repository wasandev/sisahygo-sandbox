<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Delivery_item;
use Illuminate\Auth\Access\HandlesAuthorization;

class Delivery_itemPolicy
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

    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view delivery_items');
    }
    public function view(User $user, Delivery_item $delivery_items)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view delivery_items');
    }


    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Delivery_item $delivery_item)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('edit delivery_items');
    }

    public function delete(User $user, Delivery_item $delivery_item)
    {
        if ($delivery_item->delivery_status) {
            return false;
        }
        return $user->role == 'admin' || $user->hasPermissionTo('delete delivery_items');
    }
    public function addOrder_detail(User $user, Delivery_item $delivery_item)
    {
        return false;
    }
}
