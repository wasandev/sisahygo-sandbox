<?php

namespace App\Observers;

use App\Models\Bankaccount;

class BankaccountObserver
{
    public function creating(Bankaccount $bankaccount)
    {
        $bankaccount->user_id = auth()->user()->id;
    }

    public function updating(Bankaccount $bankaccount)
    {
        $bankaccount->updated_by = auth()->user()->id;
    }
}
