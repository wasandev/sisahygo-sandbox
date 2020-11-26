<?php

namespace App\Observers;

use App\Models\Waybill;
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
    }
    public function updating(Waybill $waybill)
    {
        $waybill->updated_by = auth()->user()->id;
    }
}
