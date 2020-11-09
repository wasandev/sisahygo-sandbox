<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Serviceprice_item;
use Illuminate\Auth\Access\HandlesAuthorization;

class Serviceprice_itemPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view serviceprice_items');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create serviceprice_items');
    }


    public function update(User $user, Serviceprice_item $serviceprice_item)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit serviceprice_items');
    }


    public function delete(User $user, Serviceprice_item $serviceprice_item)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete serviceprice_items');
    }
}
