<?php

namespace App\Observers;

use App\Models\Branch_balance;
use App\Models\Branch_balance_item;
use App\Models\Branchrec_order;
use App\Models\Delivery;
use App\Models\Delivery_detail;
use App\Models\Delivery_item;
use App\Models\Order_banktransfer;
use App\Models\Order_status;
use App\Models\Receipt;
use App\Models\User;
use App\Models\Waybill_status;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Notifications\OrderPaid;

class DeliveryItemObserver
{

    public function creating(Delivery_item $delivery_item)
    {
    }

    public function updating(Delivery_item $delivery_item)
    {
        if ($delivery_item->delivery_status  && $delivery_item->payment_amount > 0) {
            //update branch_balance
            $delivery_detail = Delivery_detail::where('delivery_item_id', $delivery_item->id)->first();
            $branch_balance_item = Branch_balance_item::where('order_header_id', $delivery_detail->order_header_id)->first();
            $branch_balance = Branch_balance::where('id', '=', $branch_balance_item->branch_balance_id)
                ->where('customer_id', '=', $delivery_item->customer_id)->first();
            if ($delivery_item->payment_status && $delivery_item->branchpay_by == 'C') {
                $receipt_no = IdGenerator::generate(['table' => 'receipts', 'field' => 'receipt_no', 'length' => 15, 'prefix' => 'RC' . date('Ymd')]);
                $receipt = Receipt::create([
                    'receipt_no' => $receipt_no,
                    'receipt_date' => today(),
                    'branch_id' => auth()->user()->branch_id,
                    'customer_id' => $delivery_item->customer_id,
                    'total_amount' => $delivery_item->payment_amount,
                    'discount_amount' => $delivery_item->discount_amount,
                    'tax_amount' => $delivery_item->tax_amount,
                    'pay_amount' => $delivery_item->pay_amount,
                    'receipttype' => 'E',
                    'branchpay_by' => $delivery_item->branchpay_by,
                    'bankaccount_id' => $delivery_item->bankaccount_id,
                    'bankreference' => $delivery_item->bankreference,
                    'description' => $delivery_item->description,
                    'user_id' => auth()->user()->id,
                ]);
                $branch_balance->discount_amount = $delivery_item->discount_amount;
                $branch_balance->tax_amount = $delivery_item->tax_amount;
                $branch_balance->pay_amount = $delivery_item->pay_amount;
                $branch_balance->updated_by = auth()->user()->id;
                $branch_balance->branchpay_date = today();
                $branch_balance->payment_status = true;
                $branch_balance->remark = $delivery_item->description;
                $branch_balance->receipt_id = $receipt->id;
                $branch_balance->save();
                if (isset($receipt)) {
                    $delivery_item->receipt_id = $receipt->id;
                }
                $delivery_orders = Delivery_detail::where('delivery_item_id', $delivery_item->id)->get();
                foreach ($delivery_orders as $delivery_order) {
                    $branchrec_order = Branchrec_order::find($delivery_order->order_header_id);
                    $branchrec_order->receipt_flag = true;
                    $branchrec_order->receipt_id = $receipt->id;
                    $branchrec_order->save();
                    //test notification
                    $tousers = User::where('role', 'employee')
                        ->get();

                    foreach ($tousers as $touser) {
                        if ($touser->hasPermissionTo('view fndashboards') || $touser->hasPermissionTo('view ardashboards')) {
                            $touser->notify(new OrderPaid($touser, $branchrec_order));
                        }
                    }
                }
            }
        }
        $delivery_item->updated_by =  auth()->user()->id;
    }
    public function updated(Delivery_item $delivery_item)
    {
        $delivery = Delivery::find($delivery_item->delivery_id);
        $ordernotconfirmed = Delivery_item::where('delivery_id', $delivery->id)
            ->where('delivery_status', '=', false)
            ->count();

        if ($ordernotconfirmed == 0) {
            $delivery->completed = true;
            $delivery->save();
            //update waybill_status
            if ($delivery->delivery_type ==  0) {
                $waybill = \App\Models\Waybill::find($delivery->waybill_id);
                $waybill->waybill_status = 'completed';
                $waybill->save();
                Waybill_status::create([
                    'waybill_id' => $waybill->id,
                    'status' => 'completed',
                    'user_id' => auth()->user()->id,
                ]);
            }
        }
    }

    public function deleting(Delivery_item $delivery_item)
    {
        $delivery_details = \App\Models\Delivery_detail::where('delivery_item_id', '=', $delivery_item->id)->get();
        $delivery = \App\Models\Delivery::find($delivery_item->delivery_id);
        $receipt_amount = $delivery->receipt_amount;
        if ($receipt_amount > 0) {
            $delivery->receipt_amount = $receipt_amount - $delivery_item->payment_amount;
            $delivery->save();
        }
        foreach ($delivery_details as $delivery_detail) {

            $branchrec_order = \App\Models\Branchrec_order::find($delivery_detail->order_header_id);
            $branchrec_order->order_status = 'branch warehouse';
            $branchrec_order->save();

            Order_status::create([
                'order_header_id' => $delivery_detail->order_header_id,
                'status' => 'branch warehouse',
                'user_id' => auth()->user()->id,
            ]);
        }
    }
}
