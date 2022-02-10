<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tableprice;
use Illuminate\Auth\Access\HandlesAuthorization;

class TablepricePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return  $user->role == 'admin' || $user->hasPermissionTo('view productservice_prices');
    }
    public function view(User $user, Tableprice $tableprice)
    {


        return $user->role == 'admin' || $user->hasPermissionTo('view productservice_prices');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasAnyPermission(['create productservice_prices', 'manage own productservice_prices']);
    }


    public function update(User $user, Tableprice $tableprice)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit productservice_prices');
    }


    public function delete(User $user, Tableprice $tableprice)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('delete productservice_prices');
    }
}
