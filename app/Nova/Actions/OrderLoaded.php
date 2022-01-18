<?php

namespace App\Nova\Actions;


use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use App\Models\Waybill;
use App\Models\Order_loader;
use App\Models\Routeto_branch;
use Illuminate\Support\Arr;

class OrderLoaded extends Action
{
    use InteractsWithQueue, Queueable;



    public function uriKey()
    {
        return 'order_loaded';
    }
    public function name()
    {
        return __('Order loaded');
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

            $waybill = Waybill::find($fields->waybill_branch);

            if ($model->branch_rec_id !== $waybill->branch_rec_id) {
                return Action::danger('ใบรับส่งที่เลือกไม่ได้ไปสาขาปลายทางเดียวกันกับใบกำกับที่ต้องการจัดขึ้นสินค้า');
            }
            if ($model->order_status <> 'confirmed') {
                return Action::danger('รายการนี้จัดขึ้นไม่ได้ เนื่องจากจัดขึ้นแล้วหรือยังไม่ยืนยันใบรับส่ง');
            } else {

                $model->order_status = 'loaded';
                $model->waybill_id = $fields->waybill_branch;
                $model->save();
            }
        }
        return Action::message('ทำรายการจัดสินค้าเรียบร้อยแล้ว');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {



        $waybills = Waybill::with('car')
            ->where('waybill_status', '=', 'loading')
            ->get();
        $waybillOptions = [];

        foreach ($waybills as $waybill) {
            $waybillOptions[] = [
                ['branchwaybill' => ['id' => $waybill->id, 'name' => $waybill->waybill_no . '-' . $waybill->car->car_regist . '-' . $waybill->to_branch->name]],
            ];
        }
        $selectOptions = collect($waybillOptions)->flatten(1);

        $waybillOptions = $selectOptions->pluck('branchwaybill.name', 'branchwaybill.id');

        if (isset(request()->resourceId)) {

            $order_loader =    Order_loader::find(request()->resourceId);
            $routeto_branch = Routeto_branch::where('dest_branch_id',  $order_loader->branch_rec_id)->first();

            $waybills = Waybill::with('car')
                ->where('routeto_branch_id', '=', $routeto_branch->id)
                ->where('waybill_status', '=', 'loading')
                ->get();

            $waybillOptions = [];

            foreach ($waybills as $waybill) {
                $waybillOptions[] = [
                    ['branchwaybill' => ['id' => $waybill->id, 'name' => $waybill->waybill_no . '-' . $waybill->car->car_regist . '-' . $waybill->to_branch->name]],
                ];
            }
            $selectOptions = collect($waybillOptions)->flatten(1);

            $waybillOptions = $selectOptions->pluck('branchwaybill.name', 'branchwaybill.id');
            if (isset($waybillOptions)) {
                return [

                    Select::make(__('Waybill'), 'waybill_branch')
                        ->options($waybillOptions)
                        ->displayUsingLabels()
                        ->rules('required')
                    //->searchable(),
                ];
            }
        }


        if (isset($waybillOptions)) {
            return [

                Select::make(__('Waybill'), 'waybill_branch')
                    ->options($waybillOptions)
                    ->displayUsingLabels()
                    ->rules('required')
                //->searchable(),
            ];
        }
    }
}
