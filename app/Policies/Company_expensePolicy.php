<?php

namespace App\Policies;

use App\Models\Company_expense;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Company_expensePolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('view company_expenses');
    }


    public function create(User $user)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('create company_expenses');
    }


    public function update(User $user, Company_expense $company_expense)
    {

        return $user->role == 'admin' || $user->hasPermissionTo('edit company_expenses');
    }


    public function delete(User $user, Company_expense $company_expense)
    {
        return $user->role == 'admin' || $user->hasPermissionTo('delete compant_expenses');
    }
}
