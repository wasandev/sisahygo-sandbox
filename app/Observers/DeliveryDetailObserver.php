<?php

namespace App\Observers;

use App\Models\Branch_balance;
use App\Models\Branchrec_order;
use App\Models\Delivery;
use App\Models\Delivery_item;
use App\Models\Delivery_detail;
use App\Models\Order_banktransfer;
use App\Models\Order_status;


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
        } elseif ($branchrec_order->paymenttype == 'H') {
            $delivery_item->payment_status = true;
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

        if ($delivery_detail->delivery_status) {

            $branchrec_order = Branchrec_order::find($delivery_detail->order_header_id);


            // if ($branchrec_order->branchpay_by == "T" && $delivery_detail->payment_status  == false) {
            //     $delivery_item = Delivery_item::find($delivery_detail->delivery_item_id);

            //     Order_banktransfer::create([
            //         'customer_id' => $branchrec_order->customer_rec_id,
            //         'order_header_id' => $branchrec_order->id,
            //         'branch_id' => $branchrec_order->branch_rec_id,
            //         'status' => false,
            //         'transfer_type' => 'E',
            //         'transfer_amount' => $delivery_item->pay_amount,
            //         'bankaccount_id' => $branchrec_order->bankaccount_id,
            //         'reference' => $branchrec_order->bankreference,
            //         'user_id' => auth()->user()->id,
            //     ]);
            // }
            if ($branchrec_order->paymenttype == 'E') {

                $branch_balance = Branch_balance::where('order_header_id', $delivery_detail->order_header_id)->first();

                if ($delivery_detail->payment_status) {
                    $branch_balance->payment_status = true;
                    $branchrec_order->payment_status = true;
                    $delivery_detail->payment_status = true;
                } else {
                    $branch_balance->payment_status = false;
                    $delivery_detail->payment_status = false;
                }
                $branch_balance->save();
                $branchrec_order->save();
            }

            Order_status::updateOrCreate([
                'order_header_id' => $delivery_detail->order_header_id,
                'user_id' => auth()->user()->id,
                'status' => 'completed',
            ]);
        }
    }

    public function updated(Delivery_detail $delivery_detail)
    {

        if ($delivery_detail->payment_status) {

            $delivery_item = Delivery_item::find($delivery_detail->delivery_item_id);


            $delivery_detail_notpay = Delivery_detail::where('delivery_item_id', $delivery_item->id)
                ->where('payment_status', '=', false)
                ->count();
            // dd($delivery_detail_notpay);
            if ($delivery_detail_notpay == 0) {
                $delivery_item->payment_status = true;
            } else {
                $delivery_item->payment_status = false;
            }

            $delivery_item->save();
        }
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

        $delivery = Delivery::find($delivery_item->delivery_id);
        $ordernotconfirmed = Delivery_item::where('delivery_id', $delivery->id)
            ->where('delivery_status', '=', false)
            ->count();

        if ($ordernotconfirmed == 0) {
            $delivery->completed = true;
            $delivery->save();
        } else {
            $delivery->completed = false;
            $delivery->save();
        }
    }
}
