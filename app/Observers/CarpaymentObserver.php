<?php

namespace App\Observers;

use App\Exceptions\MyCustomException;
use App\Models\Car;
use App\Models\Car_balance;
use App\Models\Carpayment;
use App\Models\Vendor;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class CarpaymentObserver
{
    public function creating(Carpayment $carpayment)
    {
        //$carpayment->payment_date = today();
        $payment_no = IdGenerator::generate(['table' => 'carpayments', 'field' => 'payment_no', 'length' => 15, 'prefix' => 'P' . date('Ymd')]);
        $carpayment->payment_no = $payment_no;

        if (is_null($carpayment->car->vendor_id)) {
            throw new MyCustomException('รถคันนี้ยังไม่ได้ระบุข้อมูลเจ้าของรถ โปรดตรวจสอบ');
        }
        $carpayment->vendor_id = $carpayment->car->vendor_id;
        if ($carpayment->tax_flag) {
            $carpayment->tax_amount = $carpayment->amount * 0.01;
        }
        $carpayment->user_id = auth()->user()->id;
    }

    public function created(Carpayment $carpayment)
    {

        Car_balance::create([
            'car_id' => $carpayment->car_id,
            'vendor_id' => $carpayment->car->vendor_id,
            'doctype' => 'P',
            'docno' => $carpayment->payment_no,
            'cardoc_date' => $carpayment->payment_date,
            'carpayment_id' => $carpayment->id,
            'description' => $carpayment->description,
            'amount' => $carpayment->amount,
            'user_id' => auth()->user()->id,

        ]);
    }
    public function updating(Carpayment $carpayment)
    {
        $carpayment->updated_by = auth()->user()->id;
        $carpayment->vendor_id = $carpayment->car->vendor_id;
        $car_balance = Car_balance::where('carpayment_id', '=', $carpayment->id)->first();
        if (isset($car_balance)) {

            $car_balance->car_id = $carpayment->car_id;
            $car_balance->vendor_id = $carpayment->vendor_id;
            $car_balance->description = $carpayment->description;
            $car_balance->cardoc_date = $carpayment->payment_date;
            $car_balance->amount = $carpayment->amount;
            $car_balance->save();
        }

        if ($carpayment->tax_flag) {
            $carpayment->tax_amount = $carpayment->amount * 0.01;
        }
    }
    public function updated(Carpayment $carpayment)
    {
    }
    public function deleted(Carpayment $carpayment)
    {
        $car_balance = Car_balance::where('carpayment_id', '=', $carpayment->id)->first();
        if (isset($car_balance)) {
            $car_balance->delete();
        }
    }
}
