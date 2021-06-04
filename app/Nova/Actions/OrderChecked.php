<?php

namespace App\Nova\Actions;

use App\Models\Order_charter;
use App\Models\Order_checker;
use App\Models\Routeto_branch;
use App\Models\User;
use App\Models\Waybill;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class OrderChecked extends Action
{
    use InteractsWithQueue, Queueable;
    protected $model;

    public function __construct($model = null)
    {
        $this->model = $model;
    }
    public function uriKey()
    {
        return 'order_checked';
    }
    public function name()
    {
        return __('Order Checked');
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
            $hasitem = count($model->checker_details);
            //$order_amount = $model->order_details->price->sum();

            if ($model->order_status <> 'checking') {
                return Action::danger('ไม่สามารถยืนยันรายการที่ ยืนยันไปแล้วได้');
            } elseif ($hasitem) {


                $model->order_status = 'new';

                $model->waybill_id = $fields->waybill_branch;
                $model->loader_id = $fields->loader;

                $model->save();
                //return Action::message('ยืนยันรายการเรียบร้อยแล้ว');
                return Action::push('/resources/order_checkers');
            }

            return Action::danger('ไม่สามารถยืนยันรายการได้ ->ไม่มีรายการสินค้า!');
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $waybills = Waybill::where('waybill_status', 'loading')->pluck('waybill_no', 'id');
        $loaders = User::where('branch_id', '=', auth()->user()->branch_id);

        if (isset(request()->resourceId)) {
            $order_checker   =    Order_checker::find(request()->resourceId);
            $routeto_branch = Routeto_branch::where('dest_branch_id',  $order_checker->branch_rec_id)->first();

            $waybills = Waybill::with('car')
                ->where('routeto_branch_id', '=', $routeto_branch->id)
                ->where('waybill_status', '=', 'loading')
                ->get();

            foreach ($waybills as $waybill) {
                $waybillOptions[] = [
                    ['branchwaybill' => ['id' => $waybill->id, 'name' => $waybill->waybill_no . '-' . $waybill->car->car_regist]],
                ];
            }
            $selectOptions = collect($waybillOptions)->flatten(1);

            $waybillOptions = $selectOptions->pluck('branchwaybill.name', 'branchwaybill.id');



            if (isset($waybillOptions)) {
                return [

                    Select::make('สินค้าขึ้นรถแล้ว เลือกใบกำกับ', 'waybill_branch')
                        ->options($waybillOptions)
                        ->displayUsingLabels()
                        ->searchable(),
                    Select::make('พนักงานจัดขึ้น', 'loader')
                        ->options($loaders)
                        ->displayUsingLabels()
                        ->searchable(),
                ];
            }
        }
        return [

            Select::make(__('Waybill'), 'waybill_branch')
                ->options($waybills)
                ->displayUsingLabels()
                ->searchable(),
            Select::make('พนักงานจัดขึ้น', 'loader')
                ->options($loaders)
                ->displayUsingLabels()
                ->searchable(),
        ];
    }
}
