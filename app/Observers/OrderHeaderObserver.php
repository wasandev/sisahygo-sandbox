<?php

namespace App\Observers;

use App\Models\Order_header;
use App\Models\Customer;
use App\Models\Branch_area;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class OrderHeaderObserver
{
    public function creating(Order_header $order_header)
    {
        $customer_rec = Customer::find($order_header->customer_rec_id);
        $brancharea = Branch_area::where('district', $customer_rec->district)->first();

        $order_header->order_status = 'New';
        $order_header->order_header_date = today();
        $order_header->user_id = auth()->user()->id;
        $order_header->branch_id =  auth()->user()->branch_id;
        $order_header->branch_rec_id =  $brancharea->branch_id;
    }

    // public function created(Order_header $order_header)
    // {
    //     $customer_rec = Customer::find($order_header->customer_rec_id);
    //     $brancharea = DB::table('branch_areas')->where('district', $customer_rec->distinct)->first();
    //     $order_header->branch_rec_id = 2; // $brancharea->branch_id;
    // }
    public function updating(Order_header $order_header)
    {
        if ($order_header->order_status == 'Confirmed' && is_null($order_header->order_header_no)) {
            $order_header_no = IdGenerator::generate(['table' => 'order_headers', 'field' => 'order_header_no', 'length' => 15, 'prefix' => date('Ymd')]);
            $order_header->order_header_no = $order_header_no;
        }
        $order_header->updated_by = auth()->user()->id;
    }
}
