<?php

namespace App\Observers;

use App\Models\Waybill;
use App\Models\Waybill_status;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class WaybillObserver
{
    public function creating(Waybill $waybill)
    {
        $waybill->waybill_status = 'loading';
        $waybill->waybill_date = today();
        $waybill_no = IdGenerator::generate(['table' => 'waybills', 'field' => 'waybill_no', 'length' => 15, 'prefix' => 'W' . date('Ymd')]);
        $waybill->waybill_no = $waybill_no;
        $waybill->user_id = auth()->user()->id;
        Waybill_status::create([
            'waybill_id' => $waybill->id,
            'status' => 'loading',
            'user_id' => auth()->user()->id,
        ]);
    }
    public function updating(Waybill $waybill)
    {
        if ($waybill->waybill_status == 'confirmed') {
            Waybill_status::create([
                'waybill_id' => $waybill->id,
                'status' => 'confirmed',
                'user_id' => auth()->user()->id,
            ]);
        }
        if ($waybill->waybill_status == 'transporting') {
            Waybill_status::create([
                'waybill_id' => $waybill->id,
                'status' => 'transporting',
                'user_id' => auth()->user()->id,
            ]);
        }
        $waybill->updated_by = auth()->user()->id;
    }
}
