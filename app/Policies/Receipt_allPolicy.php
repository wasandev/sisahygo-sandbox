<?php

namespace App\Policies;

use App\Models\Receipt_all;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Receipt_allPolicy
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
        return $user->role == 'admin' || $user->hasPermissionTo('view receipt_all');
    }
    public function view(User $user, Receipt_all $receipt_all)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view receipt_all');
    }

    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create receipt_all');
    }

    public function update(User $user, Receipt_all $receipt)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('edit receipt_all');
    }

    public function delete(User $user, Receipt_all $receipt)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete receipt_all');
    }
}
