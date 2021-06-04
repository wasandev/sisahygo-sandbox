<?php

namespace App\Observers;

use App\Models\Branch_balance;
use App\Models\Branch_balance_partner;
use App\Models\Branch_balance_item;
use App\Models\Branchrec_waybill;
use App\Models\Branchrec_order;
use App\Models\Carpayment;
use App\Models\Order_status;
use App\Models\Routeto_branch;
use App\Models\Waybill_status;
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
                ->where('paymenttype', '=', 'E')->get();

            $cust_groups = $branch_balances->groupBy('customer_rec_id')->all();
            $bal_custs = $cust_groups;
            if ($routeto_branch->dest_branch->type == 'partner') {
                //จ่ายเงินรถด้วยรายการเก็บปลายทาง
                $payment_no = IdGenerator::generate(['table' => 'carpayments', 'field' => 'payment_no', 'length' => 15, 'prefix' => 'P' . date('Ymd')]);

                Carpayment::create([
                    'status' => true,
                    'branch_id' => $routeto_branch->branch_id,
                    'type' => 'B',
                    'payment_no' => $payment_no,
                    'car_id' => $branchrec_waybill->car_id,
                    'vendor_id' => $branchrec_waybill->car->vendor_id,
                    'payment_date' => $branchrec_waybill->waybill_date,
                    'amount' => $branch_balances->sum('order_amount'),
                    'payment_by' => 'H',
                    'tax_flag' => true,
                    'tax_amount' => $branch_balances->sum('order_amount') * 0.01,
                    'description' => 'ค่าบรรทุกสินค้าเก็บเงินปลายทาง-' . $branchrec_waybill->waybill_no,
                    'user_id' => auth()->user()->id,

                ]);
                //สร้างรายการเก็บเงินปลายทางสำหรับสาขา partner

                foreach ($bal_custs as $cust => $cust_groups) {

                    $branch_balance = Branch_balance_partner::create([
                        'branchbal_date' => $branchrec_waybill->waybill_date,
                        'branch_id' => $routeto_branch->dest_branch_id,
                        'bal_amount' => $cust_groups->sum('order_amount'),
                        'discount_amount' => 0.00,
                        'tax_amount' => 0.00,
                        'pay_amount' => 0.00,
                        'customer_id' => $cust,
                        'payment_status' => false,
                        'type' => 'partner',
                        'user_id' => auth()->user()->id,
                    ]);

                    foreach ($cust_groups as $cust_group) {
                        Branch_balance_item::create([
                            'branch_balance_id' => $branch_balance->id,
                            'order_header_id' => $cust_group->id,
                            'payment_status' => false,
                            'user_id' => auth()->user()->id,

                        ]);
                    }
                }
            } else {
                //สร้างรายการเก็บเงินปลายทางสำหรับ สาขา

                foreach ($bal_custs as $cust => $cust_groups) {

                    $branch_balance = Branch_balance::create([
                        'branchbal_date' => $branchrec_waybill->waybill_date,
                        'branch_id' => $routeto_branch->dest_branch_id,
                        'bal_amount' => $cust_groups->sum('order_amount'),
                        'discount_amount' => 0.00,
                        'tax_amount' => 0.00,
                        'pay_amount' => 0.00,
                        'customer_id' => $cust,
                        'payment_status' => false,
                        'type' => 'owner',
                        'user_id' => auth()->user()->id,
                    ]);

                    foreach ($cust_groups as $cust_group) {
                        Branch_balance_item::create([
                            'branch_balance_id' => $branch_balance->id,
                            'order_header_id' => $cust_group->id,
                            'payment_status' => false,
                            'user_id' => auth()->user()->id,

                        ]);
                    }
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
