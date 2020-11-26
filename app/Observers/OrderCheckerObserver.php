<?php

namespace App\Observers;

use App\Models\Order_checker;
use App\Models\Order_status;
use App\Models\Order_banktransfer;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class OrderCheckerObserver
{
    public function creating(Order_checker $order_checker)
    {
        $order_checker->order_status = 'checking';
        $order_checker->order_header_date = today();
        $order_checker->checker_id = auth()->user()->id;
        $order_checker->user_id = auth()->user()->id;
        $order_checker->branch_id =  auth()->user()->branch_id;
        $customer_paymenttype = $order_checker->customer->paymenttype;
        $to_customer_paymenttype = $order_checker->to_customer->paymenttype;
        if ($customer_paymenttype == 'H' || $to_customer_paymenttype == 'H') {
            $order_checker->paymenttype = 'H';
        } elseif ($customer_paymenttype == 'E' || $to_customer_paymenttype == 'E') {
            $order_checker->paymenttype = 'E';
        } elseif ($customer_paymenttype == 'Y') {
            $order_checker->paymenttype = 'F';
        } elseif ($to_customer_paymenttype == 'Y') {
            $order_checker->paymenttype = 'L';
        } else {
            $order_checker->paymenttype = 'H';
        }
    }

    public function updating(Order_checker $order_checker)
    {


        if ($order_checker->order_status == 'new') {
            $order_amount = 0;
            $order_checker->user_id = auth()->user()->id;
            $order_checker->updated_by = auth()->user()->id;
            $order_items = $order_checker->order_details;
            if ($order_checker->paymenttype == 'H') {
                $order_checker->payment_status = true;
            }
            foreach ($order_items as $order_item) {
                $sub_total = $order_item->price * $order_item->amount;
                $order_amount = $order_amount + $sub_total;
            }
            $order_checker->order_amount = $order_amount;
        }
    }
}
