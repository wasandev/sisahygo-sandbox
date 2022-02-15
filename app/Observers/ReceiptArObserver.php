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

        if ($receipt_ar->status == false) {
            $invoices = Invoice::where('receipt_id', $receipt_ar->id)->get();
            foreach ($invoices as $invoice) {
                $invoice->receipt_id = null;
                $invoice->status = 'new';
                $invoice->save();
            }
            $ar_balance_pays = Ar_balance::where('receipt_id', '=', $receipt_ar->id)
                ->where('doctype', '=', 'P')
                ->get();
            foreach ($ar_balance_pays as $ar_balance_pay) {
                $ar_balance_pay->receipt_id = null;
                $ar_balance_pay->save();
                $order_ar = Order_header::find($ar_balance_pay->order_header_id);
                $order_ar->payment_status = false;
                $order_ar->save();
            }

            $ar_balance_rec = Ar_balance::where('receipt_id', '=', $receipt_ar->id)
                ->where('doctype', '=', 'R')
                ->first();
            if (isset($ar_balance_rec)) {
                $ar_balance_rec->delete();
            }
        } else {
            $ar_balance_rec = Ar_balance::where('receipt_id', '=', $receipt_ar->id)
                ->where('doctype', '=', 'R')
                ->first();
            $ar_balance_rec->docdate = $receipt_ar->receipt_date;
            $ar_balance_rec->saveQuietly();
        }

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
    }


    public function restored(Invoice $invoice)
    {
        //
    }
}
