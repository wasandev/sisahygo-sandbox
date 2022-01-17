<?php

namespace App\Observers;

use App\Models\Branch_balance;
use App\Models\Order_header;
use App\Models\Receipt;

class Branch_balanceObserver
{
    public function updating(Branch_balance $branch_balance)
    {
        $order = Order_header::find($branch_balance->order_header_id);
        if (isset($order)) {
            $order->payment_status = $branch_balance->payment_status;
            $order->save();
        }
        if ($branch_balance->payment_status == false) {


            $receipt = Receipt::find($branch_balance->receipt_id);
            if (isset($receipt)) {
                $receipt->status = false;
                $receipt->total_amount = 0;
                $receipt->discount_amount = 0;
                $receipt->tax_amount  = 0;
                $receipt->pay_amount = 0;
                $receipt->save();
            }
        }
    }
}
