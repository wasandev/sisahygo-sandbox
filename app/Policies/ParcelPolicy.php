<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Parcel;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParcelPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view parcels');
    }

    public function view(User $user, Parcel $parcel)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view parcels');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create parcels');
    }


    public function update(User $user, Parcel $parcel)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit parcels');
    }


    public function delete(User $user, Parcel $parcel)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete parcels');
    }
}
