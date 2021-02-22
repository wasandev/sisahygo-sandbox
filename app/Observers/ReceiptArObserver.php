<?php

namespace App\Observers;

use App\Models\Ar_balance;
use App\Models\Invoice;
use App\Models\Order_header;
use App\Models\Receipt_ar;

class ReceiptArObserver
{
    public function creating(Receipt_ar $receipt_ar)
    {
    }
    public function updating(Receipt_ar $receipt_ar)
    {
        $receipt_ar->updated_by = auth()->user()->id;
    }


    public function created(Receipt_ar $receipt_ar)
    {
    }

    public function updated(Receipt_ar $invoice)
    {
        //
    }


    public function deleted(Receipt_ar $receipt_ar)
    {
        $invoices = Invoice::where('receipt_id', $receipt_ar->id);
        foreach ($invoices as $invoice) {
            $invoice->receipt_id = null;
            $invoice->save();
        }
        $ar_balances = Ar_balance::where('receipt_id', '=', $receipt_ar->id)->get();
        foreach ($ar_balances as $ar_balance) {
            $ar_balance->receipt_id = null;
            $ar_balance->save();
            $order_ar = Order_header::find($ar_balance->order_header_id);
            $order_ar->payment_status = false;
            $order_ar->save();
        }
    }


    public function restored(Invoice $invoice)
    {
        //
    }
}
