<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view quotations');
    }
    public function view(User $user, Quotation $quotation)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view quotations');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create quotations');
    }


    public function update(User $user, Quotation $quotation)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit quotations');
    }


    public function delete(User $user, Quotation $quotation)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete quotations');
    }
}
