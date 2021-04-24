<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Dropship_tran;
use Illuminate\Auth\Access\HandlesAuthorization;

class Dropship_tranPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return   $user->hasPermissionTo('view dropship_trans');
    }
    public function view(User $user, Dropship_tran $dropship_tran)
    {
        return $user->hasPermissionTo('view dropship_trans');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create dropship_trans');
    }


    public function update(User $user, Dropship_tran $dropship_tran)
    {
        return $user->hasPermissionTo('edit dropship_trans');
    }

    public function delete(User $user, Dropship_tran $dropship_tran)
    {
        return $user->hasPermissionTo('delete dropship_trans');
    }

    public function addOrder_dropship(User $user, Dropship_tran $dropship_tran)
    {

        return false;
    }
}
