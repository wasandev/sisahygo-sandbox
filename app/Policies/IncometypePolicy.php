<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Incometype;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncometypePolicy
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
        return  $user->hasPermissionTo('view incometypes');
    }
    public function view(User $user, Incometype $incometype)
    {
        return  $user->hasPermissionTo('view incometypes');
    }


    public function create(User $user)
    {
        return $user->hasPermissionTo('create incometypes');
    }


    public function update(User $user, Incometype $incometype)
    {

        return  $user->hasPermissionTo('edit incometypes');
    }


    public function delete(User $user, Incometype $Incometype)
    {

        return  $user->hasPermissionTo('delete Incometypes');
    }
}
