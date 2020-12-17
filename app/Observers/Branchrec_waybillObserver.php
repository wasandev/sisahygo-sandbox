<?php

namespace App\Observers;

use App\Models\Branchrec_waybill;
use App\Models\Branchrec_order;
use App\Models\Order_status;
use App\Models\Waybill_status;

class Branchrec_waybillObserver
{
    public function updating(Branchrec_waybill $branchrec_waybill)
    {
        if ($branchrec_waybill->waybill_status == 'arrival') {
            foreach ($branchrec_waybill->branchrec_orders as $orders) {
                Branchrec_order::where('id', $orders->id)
                    ->update(['order_status' => 'arrival']);

                Order_status::create([
                    'order_header_id' => $orders->id,
                    'status' => 'arrival',
                    'user_id' => auth()->user()->id,
                ]);
            }
            if ($branchrec_waybill->waybill_status == 'arrival') {
                Waybill_status::create([
                    'waybill_id' => $branchrec_waybill->id,
                    'status' => 'arrival',
                    'user_id' => auth()->user()->id,
                ]);
            }
        }

        $branchrec_waybill->updated_by = auth()->user()->id;
    }
}
