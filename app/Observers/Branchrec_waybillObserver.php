<?php

namespace App\Observers;

use App\Models\Branch_balance;
use App\Models\Branch_balance_partner;
use App\Models\Branchrec_waybill;
use App\Models\Branchrec_order;
use App\Models\Carpayment;
use App\Models\Order_status;
use App\Models\Routeto_branch;
use App\Models\Waybill_status;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;

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
            $routeto_branch = Routeto_branch::find($branchrec_waybill->routeto_branch_id);
            $branch_balances = Branchrec_order::where('waybill_id', $branchrec_waybill->id)
                ->where('paymenttype', '=', 'E')
                ->where('order_status', '<>', 'cancel')
                ->get();

            // $cust_groups = $branch_balances->groupBy('customer_rec_id')->all();
            // $bal_custs = $cust_groups;
            if ($routeto_branch->dest_branch->type == 'partner') {
                //จ่ายเงินรถด้วยรายการเก็บปลายทาง
                $payment_no = IdGenerator::generate(['table' => 'carpayments', 'field' => 'payment_no', 'length' => 15, 'prefix' => 'P' . date('Ymd')]);

                if (($branchrec_waybill->departure_at->month < Carbon::now()->month) && ($branchrec_waybill->departure_at->year == Carbon::now()->year)) {
                    $car_paydate = $branchrec_waybill->departure_at;
                } elseif (($branchrec_waybill->departure_at->month > Carbon::now()->month) && ($branchrec_waybill->departure_at->year < Carbon::now()->year)) {
                    $car_paydate = $branchrec_waybill->departure_at;
                } else {
                    $car_paydate = today();
                }

                Carpayment::create([
                    'status' => true,
                    'branch_id' => $routeto_branch->branch_id,
                    'type' => 'B',
                    'payment_no' => $payment_no,
                    'waybill_id' => $branchrec_waybill->id,
                    'car_id' => $branchrec_waybill->car_id,
                    'vendor_id' => $branchrec_waybill->car->vendor_id,
                    'payment_date' => $car_paydate,
                    'amount' => $branch_balances->sum('order_amount'),
                    'payment_by' => 'H',
                    'tax_flag' => true,
                    'tax_amount' => $branch_balances->sum('order_amount') * 0.01,
                    'description' => 'ค่าบรรทุกสินค้าเก็บเงินปลายทาง-' . $branchrec_waybill->waybill_no,
                    'user_id' => auth()->user()->id,

                ]);
                //สร้างรายการเก็บเงินปลายทางสำหรับสาขา partner

                foreach ($branch_balances as $branch_balance) {

                    if ($branchrec_waybill->waybill_type == 'express') {
                        $branch_balancerec = 1;
                    } else {
                        $branch_balancerec = $routeto_branch->dest_branch_id;
                    }
                    $branch_balance = Branch_balance_partner::create([
                        'branchbal_date' => today(),
                        'branch_id' => $branch_balancerec,
                        'order_header_id' => $branch_balance->id,
                        'bal_amount' => $branch_balance->order_amount,
                        'discount_amount' => 0.00,
                        'tax_amount' => 0.00,
                        'pay_amount' => 0.00,
                        'customer_id' => $branch_balance->customer_rec_id,
                        'payment_status' => false,
                        'type' => 'partner',
                        'user_id' => auth()->user()->id,
                    ]);
                }
            } else {
                //สร้างรายการเก็บเงินปลายทางสำหรับ สาขา

                foreach ($branch_balances as $branch_balance) {
                    if ($branchrec_waybill->waybill_type == 'express') {
                        $branch_balancerec = 1;
                    } else {
                        $branch_balancerec = $routeto_branch->dest_branch_id;
                    }

                    $branch_balance = Branch_balance::create([
                        'branchbal_date' => today(),
                        'branch_id' => $branch_balancerec,
                        'order_header_id' => $branch_balance->id,
                        'bal_amount' => $branch_balance->order_amount,
                        'discount_amount' => 0.00,
                        'tax_amount' => 0.00,
                        'pay_amount' => 0.00,
                        'customer_id' => $branch_balance->customer_rec_id,
                        'payment_status' => false,
                        'type' => 'owner',
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
            Waybill_status::create([
                'waybill_id' => $branchrec_waybill->id,
                'status' => 'arrival',
                'user_id' => auth()->user()->id,
            ]);
        }

        $branchrec_waybill->updated_by = auth()->user()->id;
    }
}
