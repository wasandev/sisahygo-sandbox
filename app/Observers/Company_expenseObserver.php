<?php

namespace App\Observers;

use App\Models\Company_expense;

class Company_expenseObserver
{
    public function creating(Company_expense $company_expense)
    {
        $company_expense->user_id = auth()->user()->id;
    }

    public function updating(Company_expense $company_expense)
    {
        $company_expense->updated_by = auth()->user()->id;
    }
}
