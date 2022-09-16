<?php

namespace App\Observers;

use App\Models\Order_banktransfer;
use App\Models\Order_banktransfer_item;
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
        // $tousers = User::where('role', 'employee')
        //     ->get();

        // foreach ($tousers as $touser) {

        //     if ($touser->hasPermissionTo('edit order_banktransfers')) {
        //         $touser->notify(new BankTransfer($touser, $order_banktransfer));
        //     }
        // }
    }

    public function updating(Order_banktransfer $order_banktransfer)
    {
        if ($order_banktransfer->status && $order_banktransfer->transfer_type <> 'B') {

            //new up order_banktransfer_items &&  update order_status
            if ($order_banktransfer->transfer_type == 'E') {


                $order_banktransfer_items = Order_banktransfer_item::where('order_banktransfer_id', $order_banktransfer->id)->get();
                if (isset($order_banktransfer_items)) {
                    $orderitem_count = $order_banktransfer_items->count('order_header_id');
                    if ($orderitem_count > 0) {
                        $discount_itemamount = $order_banktransfer->discount_amount / $orderitem_count;
                    } else {
                        $discount_itemamount  = $order_banktransfer->discount_amount;
                    }

                    foreach ($order_banktransfer_items as $order_banktransfer_item) {
                        $order_header = Order_header::find($order_banktransfer_item->order_header_id);
                        $order_header->payment_status = true;
                        $order_header->save();
                        //check if 'E'
                        if ($order_header->paymenttype == 'E') {
                            if ($order_header->trantype == '1') {
                                //update delivery_detail
                                $delivery_detail = \App\Models\Delivery_detail::where('order_header_id', $order_banktransfer_item->order_header_id)->first();
                                $delivery_detail->payment_status = true;
                                $delivery_detail->save();


                                //update delivery item
                                $delivery_item = \App\Models\Delivery_item::find($delivery_detail->delivery_item_id);
                                $delivery_item->payment_status = true;
                                $delivery_item->receipt_id = $order_banktransfer->receipt_id;
                                $delivery_item->save();
                            }
                            //update branch_balance
                            $branch_balance = \App\Models\Branch_balance::where('order_header_id', $order_banktransfer_item->order_header_id)->first();

                            if (isset($branch_balance)) {
                                if ($order_banktransfer->tax_amount > 0) {
                                    $balanceorder_tax = ($branch_balance->bal_amount - $discount_itemamount) * 0.01;
                                } else {
                                    $balanceorder_tax = 0;
                                }
                                $branch_balance->tax_amount = $balanceorder_tax;
                                $branch_balance->pay_amount = $branch_balance->bal_amount - $discount_itemamount - $balanceorder_tax;
                                $branch_balance->updated_by = auth()->user()->id;
                                $branch_balance->branchpay_date = $order_banktransfer->transfer_date;

                                if ($order_header->trantype == '1') {
                                    $branch_balance->remark = $branch_balance->remark . '-' . $delivery_item->description;
                                } else {
                                    $branch_balance->remark = $branch_balance->remark . '-' . 'รับสินค้าที่สาขา';
                                }
                                $branch_balance->receipt_id = $order_banktransfer->receipt_id;
                                $branch_balance->payment_status = true;
                                $branch_balance->save();
                            }
                            $branchrec_order = \App\Models\Branchrec_order::find($order_banktransfer_item->order_header_id);
                            $branchrec_order->payment_status = true;
                            $branchrec_order->save();
                        }
                    }
                }
            } else {

                $order_header = Order_header::find($order_banktransfer->order_header_id);
                $order_header->payment_status = true;
                $order_header->saveQuietly();
            }
        }

        $order_banktransfer->updated_by = auth()->user()->id;
    }
}
