<?php

namespace App\Observers;

use App\Models\Car_balance;
use App\Models\Waybill;
use App\Models\Waybill_status;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class WaybillObserver
{
    public function creating(Waybill $waybill)
    {
        if ($waybill->waybill_type <> 'charter') {
            $waybill->waybill_status = 'loading';
            $waybill->waybill_date = today();
            $waybill_no = IdGenerator::generate(['table' => 'waybills', 'field' => 'waybill_no', 'length' => 15, 'prefix' => 'W' . date('Ymd')]);
            $waybill->waybill_no = $waybill_no;
            $waybill->user_id = auth()->user()->id;
        }
    }
    public function created(Waybill $waybill)
    {
        if ($waybill->waybill_type <> 'charter') {
            if ($waybill->waybill_status == 'loading') {
                Waybill_status::create([
                    'waybill_id' => $waybill->id,
                    'status' => 'loading',
                    'user_id' => auth()->user()->id,
                ]);
            }
        }
    }
    public function updating(Waybill $waybill)
    {
        if ($waybill->waybill_type <> 'charter') {
            if ($waybill->waybill_status == 'confirmed') {

                //create car_balance
                Car_balance::updateOrCreate([
                    'car_id' => $waybill->car_id,
                    'vendor_id' => $waybill->car->vendor_id,
                    'doctype' => 'R',
                    'docno' => $waybill->waybill_no,
                    'cardoc_date' => $waybill->waybill_date,
                    'waybill_id' => $waybill->id,
                    'description' => 'ค่าขนส่งสินค้า',
                    'amount' => $waybill->waybill_payable,
                    'user_id' => auth()->user()->id,

                ]);

                Waybill_status::updateOrCreate([
                    'waybill_id' => $waybill->id,
                    'status' => 'confirmed',
                    'user_id' => auth()->user()->id,
                ]);
            }
            if ($waybill->waybill_status == 'in transit') {
                Waybill_status::updateOrCreate([
                    'waybill_id' => $waybill->id,
                    'status' => 'in transit',
                    'user_id' => auth()->user()->id,
                ]);
            }
            $waybill->updated_by = auth()->user()->id;
            $waybill->waybill_income = $waybill->waybill_amount - $waybill->waybill_payable;
        }
    }
}
