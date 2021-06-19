<?php

namespace App\Policies;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReceiptPolicy
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
        return $user->role == 'admin' || $user->hasPermissionTo('view receipt');
    }
    public function view(User $user, Receipt $receipt)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view receipt');
    }

    public function create(User $user)
    {
        //return $user->role == 'admin' || $user->hasPermissionTo('create receipt');
        return false;
    }

    public function update(User $user, Receipt $receipt)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('edit receipt');
    }

    public function delete(User $user, Receipt $receipt)
    {
        //return $user->role == 'admin' || $user->hasPermissionTo('delete receipt');
        return false;
    }
}
