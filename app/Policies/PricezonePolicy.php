<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pricezone;
use Illuminate\Auth\Access\HandlesAuthorization;

class PricezonePolicy
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
        return $user->role == 'admin' || $user->hasPermissionTo('view pricezones');
    }

    public function view(User $user, Pricezone $pricezone)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view pricezones');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create pricezones');
    }


    public function update(User $user, Pricezone $pricezone)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit pricezones');
    }


    public function delete(User $user, Pricezone $pricezone)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete pricezones');
    }
}
