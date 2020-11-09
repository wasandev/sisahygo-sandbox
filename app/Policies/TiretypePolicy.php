<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tiretype;
use Illuminate\Auth\Access\HandlesAuthorization;

class TiretypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view tiretypes');
    }

    public function view(User $user, Tiretype $tiretype)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view tiretypes');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create tiretypes');
    }


    public function update(User $user, Tiretype $tiretype)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit tiretypes');
    }


    public function delete(User $user, Tiretype $tiretype)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete tiretypes');
    }
}
