<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;
    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view customers');
    }
    public function view(User $user, Customer $customer)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view customers');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create customers');
    }


    public function update(User $user, Customer $customer)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit customers');
    }


    public function delete(User $user, Customer $customer)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete customers');
    }
}
