<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Withholdingtax;
use Illuminate\Auth\Access\HandlesAuthorization;

class WithholdingtaxPolicy
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
        return  $user->hasPermissionTo('view withholdingtaxes');
    }
    public function view(User $user, Withholdingtax $withholdingtax)
    {
        return  $user->hasPermissionTo('view withholdingtaxes');
    }


    public function create(User $user)
    {
        return $user->hasPermissionTo('create withholdingtaxes');
    }


    public function update(User $user, Withholdingtax $withholdingtax)
    {

        return  $user->hasPermissionTo('edit withholdingtaxes');
    }


    public function delete(User $user, Withholdingtax $withholdingtax)
    {

        return  $user->hasPermissionTo('delete withholdingtaxes');
    }
}
