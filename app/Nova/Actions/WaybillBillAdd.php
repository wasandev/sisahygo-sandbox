<?php

namespace App\Nova\Actions;

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
            if ($model->waybill_status == 'confirmed' || $model->waybill_status == 'in transit') {

                $order_add = Order_header::find($fields->order_add);

                //update waybill_amount
                $updated_waybillamount = $model->waybill_amount + $order_add->order_amount;
                $model->waybill_amount = $updated_waybillamount;
                //check option
                $routeto_branch = Routeto_branch::find($model->routeto_branch_id);
                $routeto_branch_cost = Routeto_branch_cost::where('routeto_branch_id', '=', $model->routeto_branch_id)
                    ->where('cartype_id', '=', $model->car->cartype_id)
                    ->first();

                if ($routeto_branch->branch->type == 'partner') {
                    $chargerate = $routeto_branch->branch->partner_rate;
                    $car_payamount = ($updated_waybillamount * $chargerate) / 100;
                    $model->waybill_payable = $car_payamount;
                } elseif ($routeto_branch->dest_branch->type == 'partner') {
                    $chargerate = $routeto_branch->dest_branch->partner_rate;
                    $car_payamount = ($updated_waybillamount * $chargerate) / 100;
                    $model->waybill_payable = $car_payamount;
                } else {

                    if ($routeto_branch_cost->chargeflag) {
                        $chargerate = $routeto_branch_cost->chargerate;
                        $car_payamount = ($updated_waybillamount * $chargerate) / 100;
                        $model->waybill_payable = $car_payamount;
                    } else {
                        $car_payamount = $model->waybill_payable;
                    }
                }

                $model->waybill_income = $updated_waybillamount - $car_payamount;
                $model->save();

                $order_add->waybill_id = $model->id;

                if ($model->waybill_status == 'in transit') {
                    $order_add->order_status = 'in transit';
                } else {
                    $order_add->order_status = 'loaded';
                }

                $order_add->save();
                if ($model->waybill_status == 'in transit') {

                    Order_status::Create([
                        'order_header_id' => $order_add->id,
                        'status' => 'in transit',
                        'user_id' => auth()->user()->id,
                    ]);
                } else {
                    Order_status::Create([
                        'order_header_id' => $order_add->id,
                        'status' => 'loaded',
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }
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
