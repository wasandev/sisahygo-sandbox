<?php

namespace App\Observers;

use App\Models\Order_header;
use App\Models\Order_status;
use App\Models\Order_banktransfer;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class OrderHeaderObserver
{
    public function creating(Order_header $order_header)
    {
        $order_amount = 0;
        $order_header->order_status = 'new';
        $order_header->order_header_date = today();
        $order_header->user_id = auth()->user()->id;
        $order_header->branch_id =  auth()->user()->branch_id;
        $customer_paymenttype = $order_header->customer->paymenttype;
        $to_customer_paymenttype = $order_header->to_customer->paymenttype;
        if ($customer_paymenttype == 'H' || $to_customer_paymenttype == 'H') {
            $order_header->paymenttype = 'H';
        } elseif ($customer_paymenttype == 'E' || $to_customer_paymenttype == 'E') {
            $order_header->paymenttype = 'E';
        } elseif ($customer_paymenttype == 'Y') {
            $order_header->paymenttype = 'F';
        } elseif ($to_customer_paymenttype == 'Y') {
            $order_header->paymenttype = 'L';
        } else {
            $order_header->paymenttype = 'H';
        }
        $order_items = $order_header->order_details;
        foreach ($order_items as $order_item) {
            $sub_total = $order_item->price * $order_item->amount;
            $order_amount = $order_amount + $sub_total;
        }
        $order_header->order_amount = $order_amount;
    }

    public function updating(Order_header $order_header)
    {


        if ($order_header->order_status == 'confirmed' && is_null($order_header->order_header_no)) {
            $order_amount = 0;
            $order_header_no = IdGenerator::generate(['table' => 'order_headers', 'field' => 'order_header_no', 'length' => 15, 'prefix' => date('Ymd')]);
            $order_header->order_header_no = $order_header_no;
            $order_header->user_id = auth()->user()->id;
            $order_header->updated_by = auth()->user()->id;
            $order_items = $order_header->order_details;
            if ($order_header->paymenttype == 'H') {
                $order_header->payment_status = true;
            }
            foreach ($order_items as $order_item) {
                $sub_total = $order_item->price * $order_item->amount;
                $order_amount = $order_amount + $sub_total;
            }
            $order_header->order_amount = $order_amount;

            Order_status::create([
                'order_header_id' => $order_header->id,
                'status' => 'confirmed',
                'user_id' => auth()->user()->id,
            ]);

            if ($order_header->paymenttype == "T") {
                Order_banktransfer::create([
                    'order_header_id' => $order_header->id,
                    'status' => false,
                    'transfer_amount' => $order_header->order_amount,
                    'bankaccount_id' => $order_header->bankaccount_id,
                    'reference' => $order_header->bankreference,
                    'user_id' => auth()->user()->id,
                ]);
            }
        }
    }
}
