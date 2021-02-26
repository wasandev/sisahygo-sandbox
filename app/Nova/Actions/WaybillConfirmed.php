<?php

namespace App\Nova\Actions;

use App\Models\Branch;
use App\Models\Routeto_branch;
use App\Models\Routeto_branch_cost;
use App\Models\Waybill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Number;

class WaybillConfirmed extends Action
{
    use InteractsWithQueue, Queueable;

    public function __construct($model = null)
    {
        $this->model = $model;
    }
    public function uriKey()
    {
        return 'waybill_confirmed';
    }
    public function name()
    {
        return __('Waybill confirmed');
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
            $hasitem = count($model->order_loaders);
            //$order_amount = $model->order_details->price->sum();

            if ($model->waybill_status <> 'loading') {
                return Action::danger('ไม่สามารถยืนยันรายการที่ ยืนยัน/ยกเลิก ไปแล้วได้');
            } elseif ($hasitem) {

                $model->waybill_amount = $fields->waybill_amount;
                $model->waybill_payable =  $fields->waybill_payable;
                //$model->waybill_income = $fields->waybill_income;
                $model->departure_at = $fields->departure_at;
                $model->arrival_at = $fields->arrival_at;
                $model->waybill_status = 'confirmed';
                $model->save();
                //return Action::message('ยืนยันรายการเรียบร้อยแล้ว');
                return Action::push('/resources/waybills/');
            }

            return Action::danger('ไม่สามารถยืนยันรายการได้ ->ไม่มีรายการใบรับส่ง!');
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
            $waybill_amount = $waybill->order_loaders->sum('order_amount');
            $routeto_branch = Routeto_branch::find($waybill->routeto_branch_id);
            $routeto_branch_cost = Routeto_branch_cost::where('routeto_branch_id', '=', $waybill->routeto_branch_id)
                ->where('cartype_id', '=', $waybill->car->cartype_id)
                ->first();

            if ($routeto_branch->branch->type == 'partner') {
                $chargerate = $routeto_branch->branch->partner_rate;
                $helptext = 'คำนวณค่าบรรทุก ' . (100 - $chargerate) . '%';
                $car_payamount = ($waybill_amount * (100 - $chargerate)) / 100;
            } elseif ($routeto_branch->dest_branch->type == 'partner') {
                $chargerate = $routeto_branch->dest_branch->partner_rate;
                $helptext = 'คำนวณค่าบรรทุก ' . (100 - $chargerate) . '%';
                $car_payamount = ($waybill_amount * (100 - $chargerate)) / 100;
            } else {
                if ($routeto_branch_cost->chargeflag) {
                    $chargerate = $routeto_branch_cost->chargerate;
                    $helptext = 'คำนวณค่าบรรทุก' . (100 - $chargerate) . '%';
                    $car_payamount = ($waybill_amount * (100 - $chargerate)) / 100;
                } else {
                    $car_payamount = $routeto_branch_cost->car_charge;
                    $helptext = 'ค่าบรรทุก ' . $routeto_branch_cost->car_charge . ' บาท';
                }
            }

            return [
                Currency::make('ยอดค่าขนส่งรวม', 'waybill_amount')->default($waybill_amount)
                    ->readonly(),
                // Number::make('%ค่าจ้างรถ', 'chargerate')->default($routeto_branch_cost->chargerate)
                //     ->readonly(),
                Currency::make('ค่าบรรทุก', 'waybill_payable')->default($car_payamount)
                    ->help($helptext),

                //Currency::make('รายได้บริษัท', 'waybill_income')->default($waybill_amount - $car_payamount),
                DateTime::make('กำหนดรถออกจากสาขาต้นทาง', 'departure_at')
                    ->format('DD/MM/YYYY HH:mm')
                    ->rules('required'),
                DateTime::make('กำหนดรถถึงสาขาปลายทาง', 'arrival_at')
                    ->format('DD/MM/YYYY HH:mm')
                    ->rules('required'),
            ];
        }
        return [
            Currency::make('ยอดค่าขนส่งรวม', 'waybill_amount')
                ->readonly(),
            Currency::make('หักค่าบรรทุก', 'waybill_payable'),
            Currency::make('รายได้บริษัท', 'waybill_income'),
            DateTime::make('กำหนดรถออกจากสาขาต้นทาง', 'departure_at')
                ->rules('required'),
            DateTime::make('กำหนดรถถึงสาขาปลายทาง', 'arrival_at')
                ->rules('required'),
        ];
    }
}
