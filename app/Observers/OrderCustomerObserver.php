<?php

namespace App\Observers;

use App\Models\Branch;
use App\Models\Branch_area;
use App\Models\Order_customer;
use App\Models\Customer;
use App\Models\Order_status;
use App\Models\Order_banktransfer;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Exceptions\MyCustomException;

class OrderCustomerObserver
{
    public function creating(Order_customer $order_customer)
    {
        $order_customer->order_status = 'checking';
        $order_customer->order_header_date = today();
        $order_customer->branch_id = 1;
        $order_customer->customer_id = 652239;
        $to_customer = Customer::find($order_customer->customer_rec_id);
        if (!isset($order_customer->branch_rec_id)) {
            $to_branch = Branch_area::where('district', '=', $to_customer->district)->first();
            if (is_null($to_branch)) {
                throw new MyCustomException('อำเภอปลายทางไม่อยู่ในพื้นที่บริการ โปรดตรวจสอบ');
            }

            $order_customer->branch_rec_id = $to_branch->branch_id;
        }

        $order_customer->checker_id = auth()->user()->id;
        $order_customer->user_id = auth()->user()->id;        
        $order_customer->paymenttype = 'F';
        $order_customer->payment_status = false;
    }

   
}
