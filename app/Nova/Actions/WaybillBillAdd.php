<?php

namespace App\Nova\Actions;

use App\Models\Branch_balance;
use App\Models\Car_balance;
use App\Models\Carpayment;
use App\Models\Order_header;
use App\Models\Order_status;
use App\Models\Routeto_branch;
use App\Models\Routeto_branch_cost;
use App\Models\Waybill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class WaybillBillAdd extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'waybill_bill_add';
    }
    public function name()
    {
        return 'นำใบรับส่งเข้าใบกำกับ';
    }
    public function __construct($model = null)
    {
        $this->model = $model;
    }
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {


            $order_add = Order_header::find($fields->order_add);
            if ($order_add->order_status  <> 'confirmed') {
                return Action::message('เพิ่มใบรับส่งได้เฉพาะรายการที่มีสถานะ -Confirmed- เท่านั้น');
            }
            $order_add->waybill_id = $model->id;
            if ($model->waybill_status == 'loading') {
                $order_add->order_status = 'loaded';
            } elseif ($model->waybill_status == 'in transit') {
                $order_add->order_status = 'in transit';
            } elseif ($model->waybill_status == 'arrival' || $model->waybill_status == 'delivery' || $model->waybill_status == 'completed') {
                $order_add->order_status = 'branch warehouse';
                //add to branch_balance
                if ($order_add->paymenttype == 'E') {
                    $branch_balance = Branch_balance::where('order_header_id', '=', $order_add->id)->first();
                    if (empty($branch_balance)) {
                        if ($model->to_branch->type == 'partner') {
                            //check in branch_balance


                            Branch_balance::create([
                                'branchbal_date' => today(),
                                'branch_id' => $model->branch_rec_id,
                                'order_header_id' => $order_add->id,
                                'bal_amount' => $order_add->order_amount,
                                'discount_amount' => 0.00,
                                'tax_amount' => 0.00,
                                'pay_amount' => 0.00,
                                'customer_id' => $order_add->customer_rec_id,
                                'payment_status' => false,
                                'type' => 'partner',
                                'user_id' => auth()->user()->id,
                            ]);
                        } else {
                            Branch_balance::create([
                                'branchbal_date' => today(),
                                'branch_id' => $model->branch_rec_id,
                                'order_header_id' => $order_add->id,
                                'bal_amount' => $order_add->order_amount,
                                'discount_amount' => 0.00,
                                'tax_amount' => 0.00,
                                'pay_amount' => 0.00,
                                'customer_id' => $order_add->customer_rec_id,
                                'payment_status' => false,
                                'type' => 'owner',
                                'user_id' => auth()->user()->id,
                            ]);
                        }
                    }
                }
            }


            $order_add->save();

            Order_status::Create([
                'order_header_id' => $order_add->id,
                'status' => $order_add->order_status,
                'user_id' => auth()->user()->id,
            ]);


            //update waybill_amount
            $updated_waybillamount = $model->order_loaders->sum('order_amount');
            $model->waybill_amount = $updated_waybillamount;
            //check option
            $routeto_branch = Routeto_branch::find($model->routeto_branch_id);
            $routeto_branch_cost = Routeto_branch_cost::where('routeto_branch_id', '=', $model->routeto_branch_id)
                ->where('cartype_id', '=', $model->car->cartype_id)
                ->first();
            //addto branch_balance


            if ($routeto_branch->branch->type == 'partner') {
                $chargerate = $routeto_branch->branch->partner_rate;
                $car_payamount = ($updated_waybillamount * (100 - $chargerate)) / 100;
                $model->waybill_payable = $car_payamount;
            } elseif ($routeto_branch->dest_branch->type == 'partner') {
                $chargerate = $routeto_branch->dest_branch->partner_rate;
                $car_payamount = ($updated_waybillamount * (100 - $chargerate)) / 100;
                $model->waybill_payable = $car_payamount;
                //update carpayment amount and tax
                if ($order_add->paymenttype == 'E') {
                    //ยอดเก็บปลายทาง
                    $branch_balance_pay = $model->order_loaders->where('paymenttype', '=', 'E')->sum('order_amount');
                    $branchpayment = Carpayment::where('waybill_id', '=', $model->id)
                        ->where('type', 'B')->first();

                    if (isset($branchpayment)) {
                        $branchpayment->amount = $branch_balance_pay;
                        $branchpayment->tax_amount =  $branch_balance_pay * 0.01;
                        $branchpayment->save();
                    }
                }
            } else {

                if ($routeto_branch_cost->chargeflag) {
                    $chargerate = $routeto_branch_cost->chargerate;
                    $car_payamount = ($updated_waybillamount * (100 - $chargerate)) / 100;
                    $model->waybill_payable = $car_payamount;
                } else {
                    $car_payamount = $model->waybill_payable;
                }
            }

            $model->waybill_income = $updated_waybillamount - $car_payamount;
            $model->save();

            //update car_balance
            $car_balance = Car_balance::where('waybill_id', $model->id)->first();
            if (isset($car_balance)) {
                $car_balance->amount = $model->waybill_payable;
                $car_balance->save();
            }

            return Action::message('ทำรายการเรียบร้อยแล้ว');
            //}
            //return Action::danger('ไม่สามารถรายการนี้ได้ ->เกินกำหนดเวลา');
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        if ($this->model) {
            $waybill = Waybill::find($this->model);
            if ($waybill->waybill_type <> 'charter') {
                $routeto_branch = Routeto_branch::find($waybill->routeto_branch_id);
                $order_add = Order_header::where('order_status', 'confirmed')
                    ->where('branch_rec_id', $routeto_branch->dest_branch_id)
                    ->pluck('order_header_no', 'id');

                return [
                    Select::make('ใบรับส่ง', 'order_add')
                        ->options($order_add)
                        ->searchable(),
                ];
            }
        }
        $order_add = Order_header::where('order_status', 'confirmed')->pluck('order_header_no', 'id');

        return [
            Select::make('ใบรับส่ง', 'order_add')
                ->options($order_add)
                ->searchable(),
        ];
    }
}
