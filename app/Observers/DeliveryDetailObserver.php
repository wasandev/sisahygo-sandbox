<?php

namespace App\Observers;

use App\Models\Branch_balance;
use App\Models\Branch_balance_item;
use App\Models\Branchrec_order;
use App\Models\Delivery;
use App\Models\Delivery_item;
use App\Models\Delivery_detail;
use App\Models\Order_banktransfer;
use App\Models\Order_status;
use App\Models\Waybill_status;

class DeliveryDetailObserver
{

    public function creating(Delivery_detail $delivery_detail)
    {
        $branchrec_order = Branchrec_order::find($delivery_detail->order_header_id);
        $branchrec_order->order_status = 'delivery';
        $branchrec_order->save();
        $delivery_item = Delivery_item::find($delivery_detail->delivery_item_id);
        $receipt_amount = $delivery_item->payment_amount;
        if ($branchrec_order->paymenttype == 'E') {
            $delivery_item->payment_amount = $receipt_amount + $branchrec_order->order_amount;
        }
        $delivery_item->save();

        $delivery = Delivery::find($delivery_item->delivery_id);
        $delivery_receipt_amount = $delivery->receipt_amount;
        if ($branchrec_order->paymenttype == 'E') {
            $delivery->receipt_amount = $delivery_receipt_amount + $branchrec_order->order_amount;
        }
        $delivery->save();
    }


    public function updating(Delivery_detail $delivery_detail)
    {
        //dd($delivery_detail->delivery_status);
        if ($delivery_detail->delivery_status) {

            $branchrec_order = Branchrec_order::find($delivery_detail->order_header_id);


            if ($branchrec_order->branchpay_by == "T" && $delivery_detail->payment_status  == false) {
                Order_banktransfer::create([
                    'customer_id' => $branchrec_order->customer_rec_id,
                    'order_header_id' => $branchrec_order->id,
                    'branch_id' => $branchrec_order->branch_rec_id,
                    'status' => false,
                    'transfer_type' => 'E',
                    'transfer_amount' => $branchrec_order->order_amount,
                    'bankaccount_id' => $branchrec_order->bankaccount_id,
                    'reference' => $branchrec_order->bankreference,
                    'user_id' => auth()->user()->id,
                ]);
            }
            if ($branchrec_order->paymenttype == 'E') {
                $branch_balance_item = Branch_balance_item::where('order_header_id', $delivery_detail->order_header_id)->first();
                if (isset($branch_balance_item)) {
                    if ($delivery_detail->payment_status) {
                        $branch_balance_item->payment_status = true;
                    } else {
                        $branch_balance_item->payment_status = false;
                    }
                    $branch_balance_item->save();
                }
            }

            Order_status::updateOrCreate([
                'order_header_id' => $delivery_detail->order_header_id,
                'status' => 'completed',
                'user_id' => auth()->user()->id,
            ]);
        }
    }
    public function updated(Delivery_detail $delivery_detail)
    {
    }

    public function deleting(Delivery_detail $delivery_detail)
    {
        $branchrec_order = Branchrec_order::find($delivery_detail->order_header_id);
        $branchrec_order->order_status = 'branch warehouse';
        $branchrec_order->save();

        $delivery_item = Delivery_item::find($delivery_detail->delivery_item_id);
        $payment_amount = $delivery_item->payment_amount;
        if ($branchrec_order->paymenttype == 'E' && $payment_amount > 0) {
            $delivery_item->payment_amount = $payment_amount - $branchrec_order->order_amount;
            $delivery_item->save();
        }
        $delivery = Delivery::find($delivery_item->delivery_id);
        $receipt_amount = $delivery->receipt_amount;
        if ($branchrec_order->paymenttype == 'E' && $receipt_amount > 0) {
            $delivery->receipt_amount = $receipt_amount - $branchrec_order->order_amount;
            $delivery->save();
        }
        Order_status::create([
            'order_header_id' => $delivery_detail->order_header_id,
            'status' => 'branch warehouse',
            'user_id' => auth()->user()->id,
        ]);
    }
}
