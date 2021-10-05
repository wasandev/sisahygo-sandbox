<?php

namespace App\Observers;

use App\Models\Billingnote_item;
use App\Models\Invoice;

class BillingnoteItemObserver
{
    public function creating(Billingnote_item $billingnote_item)
    {
        $billingnote_item->user_id = auth()->user()->id;

        $invoice = Invoice::find($billingnote_item->invoice_id);
        if (isset($invoice)) {
            $invoice->billed = true;
            $invoice->save();
        }
    }

    public function updating(Billingnote_item $billingnote_item)
    {
        $billingnote_item->updated_by = auth()->user()->id;
    }

    public function deleting(Billingnote_item $billingnote_item)
    {
        $invoice = Invoice::find($billingnote_item->invoice_id);
        if (isset($invoice)) {
            $invoice->billed = false;
            $invoice->save();
        }
    }
}
