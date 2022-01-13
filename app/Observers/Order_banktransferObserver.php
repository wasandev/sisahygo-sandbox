<?php

namespace App\Observers;

use App\Models\Order_banktransfer;
use App\Models\Order_header;
use App\Models\Receipt;
use App\Models\User;
use App\Notifications\BankTransfer;

class Order_banktransferObserver
{
    public function creating(Order_banktransfer $order_banktransfer)
    {
        $order_banktransfer->user_id = auth()->user()->id;
    }

    public function created(Order_banktransfer $order_banktransfer)
    {

        //sent notification
        $tousers = User::where('role', 'employee')
            ->get();

        foreach ($tousers as $touser) {

            if ($touser->hasPermissionTo('edit order_banktransfers')) {
                $touser->notify(new BankTransfer($touser, $order_banktransfer));
            }
        }
    }

    public function updating(Order_banktransfer $order_banktransfer)
    {
        if ($order_banktransfer->status && $order_banktransfer->transfer_type <> 'B') {

            //update order_status
            $order_header = Order_header::find($order_banktransfer->order_header_id);
            $order_header->payment_status = true;
            $order_header->save();
            //check if 'E'
            if ($order_header->paymenttype == 'E') {
                if ($order_header->trantype == '1') {
                    //update delivery_detail
                    $delivery_detail = \App\Models\Delivery_detail::where('order_header_id', $order_banktransfer->order_header_id)->first();
                    $delivery_detail->payment_status = true;
                    $delivery_detail->save();
                    //update branch_balance

                    //update delivery item
                    $delivery_item = \App\Models\Delivery_item::find($delivery_detail->delivery_item_id);
                    $delivery_item->payment_status = true;
                    $delivery_item->receipt_id = $order_banktransfer->receipt_id;
                    $delivery_item->save();
                }

                $branch_balance = \App\Models\Branch_balance::where('order_header_id', $order_banktransfer->order_header_id)->first();
                if (isset($branch_balance)) {


                    $branch_balance->pay_amount = $order_header->order_amount;
                    $branch_balance->updated_by = auth()->user()->id;
                    $branch_balance->branchpay_date = today();
                    $branch_balance->payment_status = true;
                    if ($order_header->trantype == '1') {
                        $branch_balance->remark = $delivery_item->description;
                    } else {
                        $branch_balance->remark = 'รับสินค้าที่สาขา';
                    }
                    $branch_balance->receipt_id = $order_banktransfer->receipt_id;
                    $branch_balance->payment_status = true;
                    $branch_balance->save();
                }
                $branchrec_order = \App\Models\Branchrec_order::find($order_banktransfer->order_header_id);
                $branchrec_order->payment_status = true;
                $branchrec_order->save();
            }
        } else {
        }

        $order_banktransfer->updated_by = auth()->user()->id;
    }
}
