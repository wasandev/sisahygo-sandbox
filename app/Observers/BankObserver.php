<?php

namespace App\Observers;

use App\Models\Bank;

class BankObserver
{
    public function creating(Bank $bank)
    {
        $bank->user_id = auth()->user()->id;
    }

    public function updating(Bank $bank)
    {
        $bank->updated_by = auth()->user()->id;
    }
}
