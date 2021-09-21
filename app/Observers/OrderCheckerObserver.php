<?php

namespace App\Observers;

use App\Models\Branch;
use App\Models\Branch_area;
use App\Models\Order_checker;
use App\Models\Customer;
use App\Models\Order_status;
use App\Models\Order_banktransfer;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Exceptions\MyCustomException;

class OrderCheckerObserver
{
    public function creating(Order_checker $order_checker)
    {
        $order_checker->order_status = 'checking';
        $order_checker->order_header_date = today();
        $to_customer = Customer::find($order_checker->customer_rec_id);
        if (!isset($order_checker->branch_rec_id)) {
            $to_branch = Branch_area::where('district', '=', $to_customer->district)->first();
            if (is_null($to_branch)) {
                throw new MyCustomException('อำเภอปลายทางไม่อยู่ในพื้นที่บริการ โปรดตรวจสอบ');
            }

            $order_checker->branch_rec_id = $to_branch->branch_id;
        }

        $order_checker->checker_id = auth()->user()->id;
        $order_checker->user_id = auth()->user()->id;
        $order_checker->branch_id =  auth()->user()->branch_id;
        $customer_paymenttype = $order_checker->customer->paymenttype;
        $to_customer_paymenttype = $order_checker->to_customer->paymenttype;

        if ($customer_paymenttype == 'H') {
            $order_checker->paymenttype = 'H';
        } elseif ($to_customer_paymenttype == 'H') {
            $order_checker->paymenttype = 'H';
        } elseif ($customer_paymenttype == 'E') {
            $order_checker->paymenttype = 'E';
        } elseif ($customer_paymenttype == 'Y') {
            $order_checker->paymenttype = 'F';
        } elseif ($to_customer_paymenttype == 'Y') {
            $order_checker->paymenttype = 'L';
        } else {
            $order_checker->paymenttype = 'H';
        }
        $order_checker->payment_status = false;
    }

    public function updating(Order_checker $order_checker)
    {


        if ($order_checker->order_status == 'new') {
            $order_amount = 0;
            $total_weight = 0;

            $to_customer = $order_checker->customer_rec_id;
            $to_customer = Customer::find($order_checker->customer_rec_id);
            if (!isset($order_checker->branch_rec_id)) {
                $to_branch = Branch_area::where('district', '=', $to_customer->district)->first();
                if (is_null($to_branch)) {
                    throw new MyCustomException('อำเภอปลายทางไม่อยู่ในพื้นที่บริการ โปรดตรวจสอบ');
                }

                $order_checker->branch_rec_id = $to_branch->branch_id;
            }


            $order_checker->user_id = auth()->user()->id;
            $order_checker->updated_by = auth()->user()->id;
            $order_items = $order_checker->checker_details;

            foreach ($order_items as $order_item) {
                $sub_total = $order_item->price * $order_item->amount;
                $item_weight = $order_item->weight * $order_item->amount;
                $order_amount = $order_amount + $sub_total;
                $total_weight = $total_weight +  $item_weight;
            }
            $order_checker->order_amount = $order_amount;
            $order_checker->total_weight = $total_weight;
        }
    }
}
