<?php

namespace App\Observers;

use App\Models\Ar_balance;
use App\Models\Car_balance;
use App\Models\Charter_job;
use App\Models\Charter_job_item;
use App\Models\Charter_job_status;
use App\Models\Charter_price;
use App\Models\Order_charter;
use App\Models\Order_detail;
use App\Models\Order_header;
use App\Models\Order_status;
use App\Models\Unit;
use App\Models\Waybill;
use App\Models\Waybill_status;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Carbon;


class CharterJobObserver
{
    public function creating(Charter_job $charter_job)
    {

        $charter_price = Charter_price::find($charter_job->charter_price_id);

        $charter_job->sub_total = $charter_price->price;
        $charter_job->total = $charter_price->price - $charter_job->discount;
        $charter_job->user_id = auth()->user()->id;
        $charter_job->status = 'new';
        $charter_job->paymenttype = 'F';
        $charter_job->branch_id = auth()->user()->branch_id;
        $job_no = IdGenerator::generate(['table' => 'charter_jobs', 'field' => 'job_no', 'length' => 15, 'prefix' => 'J' . date('Ymd')]);

        $charter_job->job_no = $job_no;
        $charter_job->job_date = Carbon::now()->toDateTimeString();
    }

    public function created(Charter_job $charter_job)
    {
        $charter_price = Charter_price::find($charter_job->charter_price_id);

        $charter_job->sub_total = $charter_price->price;
        $charter_job->total = $charter_price->price - $charter_job->discount;
    }


    public function updating(Charter_job $charter_job)
    {
        $charter_price = Charter_price::find($charter_job->charter_price_id);



        $charter_job->updated_by = auth()->user()->id;

        if ($charter_job->status == 'Confirmed') {

            $waybill_no = IdGenerator::generate(['table' => 'waybills', 'field' => 'waybill_no', 'length' => 15, 'prefix' => 'W' . date('Ymd')]);
            $order_header_no = IdGenerator::generate(['table' => 'order_headers', 'field' => 'order_header_no', 'length' => 15, 'prefix' => date('Ymd')]);
            $charter_job_items = Charter_job_item::where('charter_job_id', $charter_job->id)->get();
            $service_charge = $charter_job->service_charges->sum('pivot.amount');

            $waybill = Waybill::create([
                'waybill_no' => $waybill_no,
                'waybill_date' => today(),
                'waybill_type' => 'charter',
                'charter_route_id' => $charter_price->charter_route_id,
                'car_id' => $charter_job->car_id,
                'driver_id' => $charter_job->driver_id,
                'loader_id' => auth()->user()->id,
                'waybill_amount' => $charter_job->waybill_amount,
                'waybill_payable' => $charter_job->waybill_payable,
                'waybill_income' => $charter_job->waybill_amount  - $charter_job->waybill_payable,
                'branch_id' => $charter_job->branch_id,
                'branch_rec_id' => $charter_job->branch_id,
                'waybill_status' => 'confirmed',
                'departure_at' => today(),
                'user_id' => auth()->user()->id,

            ]);
            //create order_header
            $order_header = Order_header::create([
                'order_header_no' => $order_header_no,
                'order_header_date' => today(),
                'order_type' => 'charter',
                'branch_id' => $charter_job->branch_id,
                'branch_rec_id' => $charter_job->branch_id,
                'customer_id' => $charter_job->customer_id,
                'customer_rec_id' => $charter_job->customer_id,
                'paymenttype' => $charter_job->paymenttype,
                'payment_status' => false,
                'remark' => $charter_job->terms,
                'waybill_id' => $waybill->id,
                'order_amount' => $charter_job->total,
                'checker_id' =>  auth()->user()->id,
                'user_id' => auth()->user()->id,
                'order_status' => 'confirmed'

            ]);

            $charter_job->order_header_id = $order_header->id;

            $unit = Unit::where('name', '=', 'เที่ยว')->first();

            foreach ($charter_job_items as $charter_job_item) {
                $item_unit = Unit::find($charter_job_item->unit_id);
                $order_details = Order_detail::create([
                    'order_header_id' => $order_header->id,
                    'usepricetable' => false,
                    'product_id' => $charter_job_item->product_id,
                    'unit_id' => $unit->id,
                    'price' => $charter_job->waybill_amount,
                    'amount' => 1,
                    'weight' => $charter_job_item->total_weight,
                    'remark' =>  $charter_job_item->amount . ' ' . $item_unit->name,
                    'user_id' => auth()->user()->id,
                ]);
            }

            Charter_job_status::create([
                'charter_job_id' => $charter_job->id,
                'status' => 'Confirmed',
                'user_id' => auth()->user()->id,
            ]);
            //create ar_balance
            Ar_balance::create([
                'order_header_id' => $order_header->id,
                'customer_id' => $order_header->customer_id,
                'ar_amount' => $order_header->order_amount,
                'description' => 'ค่าขนส่งสินค้าเหมาคัน',
                'checker_id' => auth()->user()->id,
                'user_id' => auth()->user()->id,
                'doctype' => 'P',
                'docno' => $order_header->order_header_no,
                'docdate' => $order_header->order_header_date,

            ]);
            //Order status
            Order_status::create([
                'order_header_id' => $order_header->id,
                'status' => 'confirmed',
                'user_id' => auth()->user()->id,
            ]);

            //create car_balance
            $car_balance = Car_balance::updateOrCreate([
                'car_id' => $waybill->car_id,
                'vendor_id' => $waybill->car->vendor_id,
                'doctype' => 'R',
                'docno' => $waybill->waybill_no,
                'cardoc_date' => $waybill->waybill_date,
                'waybill_id' => $waybill->id,
                'description' => 'ค่าขนส่งสินค้าเหมาคัน',
                'amount' => $charter_job->waybill_payable,
                'user_id' => auth()->user()->id,

            ]);

            Waybill_status::updateOrCreate([
                'waybill_id' => $waybill->id,
                'status' => 'confirmed',
                'user_id' => auth()->user()->id,
            ]);
        }
    }
    public function deleting(Charter_job $charter_job)
    {
        $order_charter = Order_charter::find($charter_job->order_header_id);
        $waybill_charter = Waybill::find($order_charter->waybill_id);
        if (isset($order_charter)) {

            $order_charter->order_status = 'cancel';
            $order_charter->save();
            Order_status::create([
                'order_header_id' => $charter_job->order_header_id,
                'status' => 'cancel',
                'user_id' => auth()->user()->id,
            ]);
            $ar_balance = Ar_balance::where('order_header_id', $order_charter->id)->delete();
        }


        if (isset($waybill_charter)) {

            $waybill_charter->waybill_status = 'cancel';
            $waybill_charter->save();
            Waybill_status::create([
                'waybill_id' => $waybill_charter->id,
                'status' => 'cancel',
                'user_id' => auth()->user()->id,
            ]);
            $car_balance = Car_balance::where('waybill_id', $waybill_charter->id)->delete();
        }
    }
}
