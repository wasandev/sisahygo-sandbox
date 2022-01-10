<?php

namespace App\Observers;

use App\Models\Branchrec_order;
use App\Models\Order_header;
use App\Models\Order_problem;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Order_problemObserver
{
    public function creating(Order_problem $order_problem)
    {
        $problem_no = IdGenerator::generate(['table' => 'order_problems', 'field' => 'problem_no', 'length' => 15, 'prefix' => 'QC' . date('Ymd')]);
        if (auth()->user()->branch_id == 1) {
            $order_header = Order_header::find($order_problem->order_header_id);
            if ($order_problem->customer_flag == 'S') {
                $order_problem->customer_id = $order_header->customer->id;
            } else {
                $order_problem->customer_id = $order_header->to_customer->id;
            }
            $order_problem->problem_no = $problem_no;
            $order_problem->problem_date = today();
            $order_problem->user_id = auth()->user()->id;

            $order_header->order_status = 'problem';
            $order_header->save();
        } else {
            $branchrec_order = Branchrec_order::find($order_problem->order_header_id);
            if ($order_problem->customer_flag == 'S') {
                $order_problem->customer_id = $branchrec_order->customer->id;
            } else {
                $order_problem->customer_id = $branchrec_order->to_customer->id;
            }
            $order_problem->problem_no = $problem_no;
            $order_problem->problem_date = today();
            $order_problem->user_id = auth()->user()->id;

            $branchrec_order->order_status = 'problem';
            $branchrec_order->save();
        }
    }

    public function updating(Order_problem $order_problem)
    {
        if ($order_problem->status == 'new') {
            $order_problem->status = 'checking';
            $order_problem->checker_id = auth()->user()->id;
        } elseif ($order_problem->status == 'checking') {
            $order_problem->status = 'discuss';
        } elseif ($order_problem->status == 'discuss' && $order_problem->approve_amount > 0) {
            $order_problem->status = 'approved';
        }
    }
}
