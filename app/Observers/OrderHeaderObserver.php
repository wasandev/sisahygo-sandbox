<?php

namespace App\Observers;

use App\Models\Order_header;
use App\Models\Order_status;
use App\Models\Order_banktransfer;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Carbon;
use App\Models\Customer;
use App\Models\Branch_area;
use App\Exceptions\MyCustomException;
use App\Models\Ar_balance;
use App\Models\Waybill;
use App\Nova\Actions\OrderConfirmed;
use App\Nova\Actions\PrintOrder;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Nova\Actions\DispatchAction;
use Laravel\Nova\Http\Requests\ActionRequest;

class OrderHeaderObserver
{
    public function creating(Order_header $order_header)
    {
        if ($order_header->order_type <> 'charter') {
            $order_amount = 0;
            $order_header->order_status = 'new';
            $order_header->order_header_date = today();
            $order_header->user_id = auth()->user()->id;
            $order_header->branch_id =  auth()->user()->branch_id;
            $branch = \App\Models\Branch::find(auth()->user()->branch_id);
            if ($branch->dropship_flag) {
                $order_header->shipto_center  = '0';
            }
            $to_customer = Customer::find($order_header->customer_rec_id);
            if (!isset($order_header->branch_rec_id)) {
                $to_branch = Branch_area::where('district', '=', $to_customer->district)->first();
                if (is_null($to_branch)) {
                    throw new MyCustomException('อำเภอปลายทางไม่อยู่ในพื้นที่บริการ โปรดตรวจสอบ');
                }

                $order_header->branch_rec_id = $to_branch->branch_id;
            }
            $customer_paymenttype = $order_header->customer->paymenttype;
            $to_customer_paymenttype = $order_header->to_customer->paymenttype;

            if ($customer_paymenttype == 'H' || $to_customer_paymenttype == 'H') {
                $order_header->paymenttype = 'H';
            } 
            if ($customer_paymenttype == 'E' || $to_customer_paymenttype == 'E') {
                $order_header->paymenttype = 'E';
            } 
            if ($customer_paymenttype == 'E' || $to_customer_paymenttype == 'H') {
                $order_header->paymenttype = 'E';
            } 
            if ($customer_paymenttype == 'Y') {
                $order_header->paymenttype = 'F';
            } 
            if ($to_customer_paymenttype == 'Y') {
                $order_header->paymenttype = 'L';
            } 
            
            $order_items = $order_header->order_details;
            foreach ($order_items as $order_item) {
                $sub_total = $order_item->price * $order_item->amount;
                $order_amount = $order_amount + $sub_total;
            }
            $order_header->order_amount = $order_amount;
            $order_header->payment_status = false;
        }
    }

    public function updating(Order_header $order_header)
    {
        if ($order_header->order_type <> 'charter') {
            if ($order_header->getOriginal('order_status') <> 'problem') {
                $order_amount = 0;
                $branch = \App\Models\Branch::find(auth()->user()->branch_id);
                if ($branch->dropship_flag) {
                    $order_header->shipto_center  = '0';
                }
                $to_customer = Customer::find($order_header->customer_rec_id);
                if (!isset($order_header->branch_rec_id)) {
                    $to_branch = Branch_area::where('district', '=', $to_customer->district)->first();
                    if (is_null($to_branch)) {
                        throw new MyCustomException('อำเภอปลายทางไม่อยู่ในพื้นที่บริการ โปรดตรวจสอบ');
                    }

                    $order_header->branch_rec_id = $to_branch->branch_id;
                }


                $order_items = $order_header->order_details;
                foreach ($order_items as $order_item) {
                    $sub_total = $order_item->price * $order_item->amount;
                    $order_amount = $order_amount + $sub_total;
                }
                $order_header->order_amount = $order_amount;
                $order_header->payment_status = false;
                if ($order_header->order_status == 'confirmed' && is_null($order_header->order_header_no)) {
                    $order_amount = 0;
                    $total_weight = 0;
                    $branchtrack = $order_header->branch->code . $order_header->to_branch->code;
                    $order_header_no = IdGenerator::generate(['table' => 'order_headers', 'field' => 'order_header_no', 'length' => 15, 'prefix' => date('Ymd')]);
                    //$tracking_no = IdGenerator::generate(['table' => 'order_headers', 'field' => 'tracking_no', 'length' => 20, 'prefix' =>  date('Ymd')]);

                    $order_header->order_header_no = $order_header_no;
                    //$order_header->tracking_no = $tracking_no;

                    $to_customer = Customer::find($order_header->customer_rec_id);
                    if (!isset($order_header->branch_rec_id)) {
                        $to_branch = Branch_area::where('district', '=', $to_customer->district)->first();
                        if (is_null($to_branch)) {
                            throw new MyCustomException('อำเภอปลายทางไม่อยู่ในพื้นที่บริการ โปรดตรวจสอบ');
                        }

                        $order_header->branch_rec_id = $to_branch->branch_id;
                    }
                    $order_header->order_header_date = today();
                    $order_header->created_at = Carbon::now()->toDateTimeString();
                    $order_header->user_id = auth()->user()->id;
                    $order_header->updated_by = auth()->user()->id;
                    $order_items = $order_header->order_details;
                    if ($order_header->paymenttype == 'H') {
                        $order_header->payment_status = true;
                    }
                    foreach ($order_items as $order_item) {
                        $sub_total = $order_item->price * $order_item->amount;
                        $item_weight = $order_item->weight * $order_item->amount;
                        $order_amount = $order_amount + $sub_total;
                        $total_weight = $total_weight +  $item_weight;
                    }

                    $order_header->order_amount = $order_amount;
                    $order_header->total_weight = $total_weight;



                    if ($order_header->paymenttype == "T") {
                        Order_banktransfer::create([
                            'customer_id' => $order_header->customer_id,
                            'order_header_id' => $order_header->id,
                            'branch_id' => $order_header->branch_id,
                            'status' => false,
                            'transfer_amount' => $order_header->order_amount,
                            'bankaccount_id' => $order_header->bankaccount_id,
                            'reference' => $order_header->bankreference,
                            'transfer_type' => 'H',
                            'user_id' => auth()->user()->id,
                        ]);
                    }
                    if ($order_header->paymenttype == "F") {
                        Ar_balance::create([
                            'order_header_id' => $order_header->id,
                            'doctype' => 'P',
                            'docno' => $order_header->order_header_no,
                            'docdate' => $order_header->order_header_date,
                            'customer_id' => $order_header->customer_id,
                            'ar_amount' => $order_header->order_amount,
                            'description' => 'ค่าขนส่งสินค้า',
                            'user_id' => auth()->user()->id,
                            'branch_id' => $order_header->branch_id,
                        ]);
                    }
                    if ($order_header->paymenttype == "L") {
                        Ar_balance::create([
                            'order_header_id' => $order_header->id,
                            'doctype' => 'P',
                            'docno' => $order_header->order_header_no,
                            'docdate' => $order_header->order_header_date,
                            'customer_id' => $order_header->customer_rec_id,
                            'ar_amount' => $order_header->order_amount,
                            'description' => 'ค่าขนส่งสินค้า',
                            'user_id' => auth()->user()->id,
                            'branch_id' => $order_header->branch_rec_id,
                        ]);
                    }

                    Order_status::create([
                        'order_header_id' => $order_header->id,
                        'status' => 'confirmed',
                        'user_id' => auth()->user()->id,
                    ]);
                    if (isset($order_header->waybill_id)) {
                        $order_header->order_status = 'loaded';
                        Order_status::create([
                            'order_header_id' => $order_header->id,
                            'status' => 'loaded',
                            'user_id' => auth()->user()->id,
                        ]);
                    }
                }

                if ($order_header->order_status == 'cancel') {
                    switch ($order_header->paymenttype) {
                        case 'H':
                            //receipt
                            $receipt_item = \App\Models\Receipt_item::where('order_header_id', $order_header->id)->delete();
                            break;
                        case 'F':
                            $ar_balance  = \App\Models\Ar_balance::where('order_header_id', $order_header->id)->delete();
                            break;
                        case 'L':

                            $ar_balance  = \App\Models\Ar_balance::where('order_header_id', $order_header->id)->delete();
                            break;
                        case 'T':
                            $banktransfer = \App\Models\Order_banktransfer::where('order_header_id', $order_header->id)->delete();
                            break;
                        case 'E':
                            $branch_balance = \App\Models\Branch_balance::where('order_header_id', $order_header->id)->delete();
                            break;
                    }

                    if ($order_header->branchpay_by == 'T') {
                        $banktransfer_branch = \App\Models\Order_banktransfer::where('order_header_id', $order_header->id)->delete();
                    }
                }
            }
        }
    }
}
