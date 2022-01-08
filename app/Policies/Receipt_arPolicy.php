<?php

namespace App\Policies;

use App\Models\Receipt_ar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Receipt_arPolicy
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
        return $user->role == 'admin' || $user->hasPermissionTo('view receipt_ar');
    }
    public function view(User $user, Receipt_ar $receipt_ar)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view receipt_ar');
    }

    public function create(User $user)
    {
        //return $user->role == 'admin' || $user->hasPermissionTo('create receipt_ar');
        return false;
    }

    public function update(User $user, Receipt_ar $receipt_ar)
    {
        return  $user->hasPermissionTo('edit receipt');
    }

    public function delete(User $user, Receipt_ar $receipt)
    {
        //return $user->role == 'admin' || $user->hasPermissionTo('delete receipt_ar');
        return false;
    }
}
