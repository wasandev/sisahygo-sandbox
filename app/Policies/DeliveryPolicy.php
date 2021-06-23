<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Delivery;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliveryPolicy
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

    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view deliveries');
    }
    public function view(User $user, Delivery $delivery)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view deliveries');
    }


    public function create(User $user)
    {
        return false;
    }


    public function update(User $user, Delivery $delivery)
    {
        return (($user->role == 'admin') || ($user->hasPermissionTo('edit deliveries') && $delivery->completed == false));
    }


    public function delete(User $user, Delivery $delivery)
    {
        if ($delivery->completed) {
            return false;
        }
        return $user->role == 'admin' || $user->hasPermissionTo('delete deliveries');
    }
}
