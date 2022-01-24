<?php

namespace App\Observers;

use App\Models\Branch_balance;
use App\Models\Delivery_detail;
use App\Models\Order_header;
use App\Models\Receipt;

class Branch_balanceObserver
{
    public function updated(Branch_balance $branch_balance)
    {
        // $order = Order_header::find($branch_balance->order_header_id);
        // $delivery_detail = Delivery_detail::where('order_header_id', $order->id)->first();

        // if (isset($delivery_detail)) {

        //     if ($branch_balance->payment_status) {
        //         $delivery_detail->payment_status = true;
        //         $order->payment_status = true;
        //         $order->save();
        //     } else {
        //         $delivery_detail->payment_status = false;
        //         $receipt = Receipt::find($branch_balance->receipt_id);
        //         if (isset($receipt)) {
        //             $receipt->status = false;
        //             $receipt->total_amount = 0;
        //             $receipt->discount_amount = 0;
        //             $receipt->tax_amount  = 0;
        //             $receipt->pay_amount = 0;
        //             $receipt->save();
        //         }
        //     }
        // }
    }
}
