<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
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
        return $user->role == 'admin' || $user->hasPermissionTo('view invoices');
    }
    public function view(User $user, Invoice $invoice)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view invoices');
    }


    public function create(User $user)
    {
        //return $user->role == 'admin' || $user->hasPermissionTo('create invoices');
        return false;
    }


    public function update(User $user, Invoice $invoice)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit invoices');
    }


    public function delete(User $user, Invoice $invoice)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('delete invoices');
    }
}
