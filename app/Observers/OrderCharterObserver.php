<?php

namespace App\Observers;

use App\Models\Order_charter;
use App\Models\Order_status;
use App\Models\Order_banktransfer;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Carbon;
use App\Models\Customer;
use App\Models\Branch_area;
use App\Exceptions\MyCustomException;
use App\Models\Ar_balance;

class OrderCharterObserver
{
    public function creating(Order_charter $order_charter)
    {
    }


    public function updating(Order_charter $order_charter)
    {

        if ($order_charter->order_status == 'cancel') {
            $ar_balance  = \App\Models\Ar_balance::where('order_header_id', $order_charter->id)->delete();
        }
    }
}
