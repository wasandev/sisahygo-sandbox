<?php

namespace App\Observers;

use App\Models\Branch_balance;
use App\Models\Branchrec_order;
use App\Models\Delivery;
use App\Models\Delivery_detail;
use App\Models\Delivery_item;
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
