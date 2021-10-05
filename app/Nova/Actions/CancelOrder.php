<?php

namespace App\Nova\Actions;

use App\Models\Car_balance;
use App\Models\Delivery_detail;
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
use Laravel\Nova\Fields\Text;

class CancelOrder extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'cancel_order';
    }
    public function name()
    {
        return 'ยกเลิกใบรับส่ง';
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

            if ($model->order_status != 'cancel' || $model->order_status != 'completed' || $model->order_status != 'new') {
                $model->order_status = 'cancel';
                $model->remark = $model->remark . 'สาเหตุที่ยกเลิก' . $fields->remark;

                if (!empty($model->waybill_id)) {
                    $waybill = Waybill::find($model->waybill_id);
                    $model->waybill_id = null;

                    $model->save();


                    //remove from waybill and update car_balance
                    //check if in deliever_detail
                    $deliver_detail = Delivery_detail::where('order_header_id', $model->id)->delete();

                    //update waybill_amount

                    $updated_waybillamount =   $waybill->order_loaders->sum('order_amount');
                    $waybill->waybill_amount = $updated_waybillamount;
                    //check option
                    $routeto_branch = Routeto_branch::find($waybill->routeto_branch_id);
                    $routeto_branch_cost = Routeto_branch_cost::where('routeto_branch_id', '=', $waybill->routeto_branch_id)
                        ->where('cartype_id', '=', $waybill->car->cartype_id)
                        ->first();

                    if ($routeto_branch->branch->type == 'partner') {
                        $chargerate = $routeto_branch->branch->partner_rate;

                        $car_payamount = ($updated_waybillamount * (100 - $chargerate)) / 100;
                        $waybill->waybill_payable = $car_payamount;
                    } elseif ($routeto_branch->dest_branch->type == 'partner') {
                        $chargerate = $routeto_branch->dest_branch->partner_rate;

                        $car_payamount = ($updated_waybillamount * (100 - $chargerate)) / 100;
                        $waybill->waybill_payable = $car_payamount;
                    } else {

                        if ($routeto_branch_cost->chargeflag) {
                            $chargerate = $routeto_branch_cost->chargerate;
                            $car_payamount = ($updated_waybillamount * (100 - $chargerate)) / 100;
                            $waybill->waybill_payable = $car_payamount;
                        } else {
                            $car_payamount = $waybill->waybill_payable;
                        }
                    }

                    $waybill->waybill_income = $updated_waybillamount - $car_payamount;
                    $waybill->save();

                    //update car_balance
                    $car_balance = Car_balance::where('waybill_id', $waybill->id)->first();
                    if (isset($car_balance)) {
                        $car_balance->amount = $waybill->waybill_payable;
                        $car_balance->save();
                    }
                }
                $model->save();
                Order_status::create([
                    'order_header_id' => $model->id,
                    'status' => 'cancel',
                    'user_id' => auth()->user()->id,
                ]);


                return Action::message('ยกเลิกรายการเรียบร้อยแล้ว');
                //return Action::push('/resources/order_headers/');
            }
            return Action::danger('ไม่สามารถยกเลิกรายการนี้ได้');
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make('ระบุสาเหตุที่ยกเลิก', 'remark')->rules('required'),
        ];
    }
}
