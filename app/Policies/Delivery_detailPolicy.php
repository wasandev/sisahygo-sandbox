<?php

namespace App\Policies;

use App\Models\Delivery_detail;
use App\Models\Delivery_item;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Delivery_detailPolicy
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
    public function view(User $user, Delivery_detail $delivery_detail)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view delivery_items');
    }


    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Delivery_detail $delivery_detail)
    {
        return false;
    }

    public function delete(User $user, Delivery_detail $delivery_detail)
    {
        $delivery_item = Delivery_item::find($delivery_detail->delivery_item_id);

        if ($delivery_item->payment_status || $delivery_item->delivery_status) {
            return false;
        }
        return $user->role == 'admin' || $user->hasPermissionTo('delete delivery_items');
    }
    public function addOrder_detail(User $user, Delivery_detail $delivery_detail)
    {
        return false;
    }
}
