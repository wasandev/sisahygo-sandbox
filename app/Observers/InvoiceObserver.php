<?php

namespace App\Observers;

use App\Models\Ar_balance;
use App\Models\Invoice;

class InvoiceObserver
{

    public function creating(Invoice $invoice)
    {
    }
    public function updating(Invoice $invoice)
    {
        $invoice->updated_by = auth()->user()->id;
    }


    public function created(Invoice $invoice)
    {
    }

    public function updated(Invoice $invoice)
    {
        //
    }


    public function deleted(Invoice $invoice)
    {
        $ar_balances = Ar_balance::where('invoice_id', '=', $invoice->id)->get();
        foreach ($ar_balances as $ar_balance) {
            $ar_balance->invoice_id = null;
            $ar_balance->save();
        }
    }


    public function restored(Invoice $invoice)
    {
        //
    }
}
