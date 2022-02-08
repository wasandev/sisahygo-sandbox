<?php

namespace App\Observers;

use App\Models\Car;
use App\Models\Car_balance;
use App\Models\Carpayment;
use App\Models\Carreceive;

class CarObserver
{
    public function creating(Car $car)
    {
        $car->user_id = auth()->user()->id;
        $car->status = '1';
    }

    public function updating(Car $car)
    {
        // $car->updated_by = auth()->user()->id;
        // $newvendor = $car->vendor_id;
        // $oldvendor = $car->getOriginal('vendor_id');

        // if ($newvendor != $oldvendor) {
        //     //Update vendor_id value in car_balance ,car_payment,car_receive
        //     //car_balance
        //     $car_balances = Car_balance::where('car_id', $car->id)
        //         ->whereNotNull('waybill_id')
        //         ->whereYear('cardoc_date', date("Y"))
        //         ->whereMonth('cardoc_date', date("m"))
        //         ->get();


        //     foreach ($car_balances as $car_balance) {
        //         $car_balance->vendor_id = $newvendor;
        //         $car_balance->save();
        //     }


        //     //carpayment
        //     $car_payments = Carpayment::where('car_id', $car->id)
        //         ->whereYear('payment_date', date("Y"))
        //         ->whereMonth('payment_date', date("m"))
        //         ->get();
        //     foreach ($car_payments as $car_payment) {
        //         $car_payment->vendor_id = $newvendor;
        //         $car_payment->save();
        //     }

        //     //carreceive

        //     $car_receives = Carreceive::where('car_id', $car->id)
        //         ->whereYear('receive_date', date("Y"))
        //         ->whereMonth('receive_date', date("m"))
        //         ->get();
        //     foreach ($car_receives as $car_receive) {
        //         $car_receive->vendor_id = $newvendor;
        //         $car_receive->save();
        //     }
        // }
    }
}
