<?php

namespace App\Observers;

use App\Exceptions\MyCustomException;
use App\Models\Car_balance;
use App\Models\Carreceive;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class CarreceiveObserver
{
    public function creating(Carreceive $carreceive)
    {
        //$carreceive->receive_date = today();
        $receive_no = IdGenerator::generate(['table' => 'carreceives', 'field' => 'receive_no', 'length' => 15, 'prefix' => 'R' . date('Ymd')]);
        $carreceive->receive_no = $receive_no;
        if (is_null($carreceive->car->vendor_id)) {
            throw new MyCustomException('รถคันนี้ยังไม่ได้ระบุข้อมูลเจ้าของรถ โปรดตรวจสอบ');
        }
        $carreceive->vendor_id = $carreceive->car->vendor_id;

        $carreceive->user_id = auth()->user()->id;
    }

    public function created(Carreceive $carreceive)
    {

        Car_balance::create([
            'car_id' => $carreceive->car_id,
            'vendor_id' => $carreceive->car->vendor_id,
            'doctype' => 'R',
            'docno' => $carreceive->receive_no,
            'cardoc_date' => $carreceive->receive_date,
            'carreceive_id' => $carreceive->id,
            'description' => $carreceive->description,
            'amount' => $carreceive->amount,
            'user_id' => auth()->user()->id,

        ]);
    }
    public function updating(Carreceive $carreceive)
    {
        $carreceive->updated_by = auth()->user()->id;
        $car_balance = Car_balance::where('carreceive_id', '=', $carreceive->id)->first();
        if (isset($car_balance)) {

            $car_balance->car_id = $carreceive->car_id;
            $car_balance->vendor_id = $carreceive->vendor_id;
            $car_balance->description = $carreceive->description;
            $car_balance->amount = $carreceive->amount;
            $car_balance->save();
        }
    }

    public function deleted(Carreceive $carreceive)
    {
        $car_balance = Car_balance::where('carreceive_id', '=', $carreceive->id)->first();
        if (isset($car_balance)) {
            $car_balance->delete();
        }
    }
}
