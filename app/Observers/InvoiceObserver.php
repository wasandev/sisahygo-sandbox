<?php

namespace App\Observers;

use App\Models\Ar_balance;
use App\Models\Invoice;
use Illuminate\Support\Carbon;

class InvoiceObserver
{

    public function creating(Invoice $invoice)
    {
        $invoice_date = Carbon::parse($invoice->invoice_date);
        $invoice->due_date = $invoice_date->addDays($invoice->ar_customer->creditterm);
    }
    public function updating(Invoice $invoice)
    {
        $invoice_date = Carbon::parse($invoice->invoice_date);
        $invoice->due_date = $invoice_date->addDays($invoice->ar_customer->creditterm);
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
