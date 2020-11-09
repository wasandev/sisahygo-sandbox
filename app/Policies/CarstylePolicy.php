<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Carstyle;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarstylePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view carstyles');
    }
    public function view(User $user, Carstyle $carstyle)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view carstyles');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create carstyles');
    }


    public function update(User $user, Carstyle $carstyle)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit carstyles');
    }


    public function delete(User $user, Carstyle $carstyle)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete carstyles');
    }
}
