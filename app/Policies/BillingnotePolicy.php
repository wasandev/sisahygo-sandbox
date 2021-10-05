<?php

namespace App\Policies;

use App\Models\Billingnote;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillingnotePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view billingnotes');
    }
    public function view(User $user, Billingnote $billingnote)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view billingnotes');
    }


    public function create(User $user)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('create billingnotes');
    }


    public function update(User $user, Billingnote $billingnote)
    {


        return $user->role == 'admin' || $user->hasPermissionTo('edit billingnotes');
    }


    public function delete(User $user, Billingnote $billingnote)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete billingnotes');
    }
}
