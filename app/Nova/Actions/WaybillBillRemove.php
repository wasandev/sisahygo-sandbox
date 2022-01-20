<?php

namespace App\Nova\Actions;

use App\Models\Branch_balance;
use App\Models\Car_balance;
use App\Models\Carpayment;
use App\Models\Delivery_detail;
use App\Models\Order_loader;
use App\Models\Order_status;
use App\Models\Routeto_branch;
use App\Models\Routeto_branch_cost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class WaybillBillRemove extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'waybill_bill_remove';
    }
    public function name()
    {
        return 'นำใบรับส่งออกจากใบกำกับ';
    }
    public function __construct($model = null)
    {
        $this->model = $model;
    }
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            //if ($model->waybill_status != 'completed') {

            $order_remove = Order_loader::find($fields->order_remove);
            if ($order_remove->order_status == 'completed') {
                return Action::message('ไม่สามารถนำใบรับส่งรายการนี้ออกได้ เนื่องจากทำการจัดส่งแล้ว');
            }

            $order_remove->waybill_id = null;
            $order_remove->order_status = 'confirmed';
            $order_remove->save();

            //check if branch_balance
            $branch_balance = Branch_balance::where('order_header_id', $order_remove->id)->delete();
            //check if in deliever_detail
            $deliver_detail = Delivery_detail::where('order_header_id', $order_remove->id)->delete();

            //update waybill_amount
            $updated_waybillamount =   $model->order_loaders->sum('order_amount');
            $model->waybill_amount = $updated_waybillamount;
            //check option
            $routeto_branch = Routeto_branch::find($model->routeto_branch_id);
            $routeto_branch_cost = Routeto_branch_cost::where('routeto_branch_id', '=', $model->routeto_branch_id)
                ->where('cartype_id', '=', $model->car->cartype_id)
                ->first();

            if ($routeto_branch->branch->type == 'partner') {
                $chargerate = $routeto_branch->branch->partner_rate;

                $car_payamount = ($updated_waybillamount * (100 - $chargerate)) / 100;
                $model->waybill_payable = $car_payamount;
            } elseif ($routeto_branch->dest_branch->type == 'partner') {
                $chargerate = $routeto_branch->dest_branch->partner_rate;

                $car_payamount = ($updated_waybillamount * (100 - $chargerate)) / 100;
                $model->waybill_payable = $car_payamount;
                //update carpayment amount and tax
                $branchpayment = Carpayment::where('waybill_id', '=', $model->id)
                    ->where('type', 'B')->first();

                if (isset($branchpayment)) {
                    $current_payamount = $branchpayment->amount;
                    $branchpayment->amount = $current_payamount - $order_remove->order_amount;
                    $branchpayment->tax_amount =  ($current_payamount - $order_remove->order_amount) * 0.01;
                    $branchpayment->save();
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
            $order_waybill = Order_loader::where('waybill_id', $this->model)->pluck('order_header_no', 'id');

            return [
                Select::make('ใบรับส่ง', 'order_remove')
                    ->options($order_waybill)
                    ->searchable(),
            ];
        }
        $order_waybill = Order_loader::where('waybill_id', $this->model)->pluck('order_header_no', 'id');

        return [
            Select::make('ใบรับส่ง', 'order_remove')
                ->options($order_waybill)
                ->searchable(),
        ];
    }
}
