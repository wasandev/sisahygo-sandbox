<?php

namespace App\Policies;

use App\Models\Receipt_item;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Receipt_itemPolicy
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
        return $user->role == 'admin' || $user->hasPermissionTo('view receipt_item');
    }
    public function view(User $user, Receipt_item $receipt_item)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view receipt_item');
    }

    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create receipt_item');
    }

    public function update(User $user, Receipt_item $receipt_item)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('edit receipt_item');
    }

    public function delete(User $user, Receipt_item $receipt_item)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete receipt_item');
    }
}
