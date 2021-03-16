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

class OrderLoaded extends Action
{
    use InteractsWithQueue, Queueable;
    protected $model;

    // public function __construct($model = null)
    // {
    //     $this->model = $model;
    // }
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


            if ($model->order_status == 'loaded') {
                return Action::danger('รายการนี้จัดขึ้นแล้ว');
            } else {

                $model->order_status = 'loaded';
                $model->waybill_id = $fields->waybill_branch;
                $model->save();
            }
            //return Action::push('/resources/order_loaders/');
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
        //  if (isset(request()->resourceId)) {

        // $order_loader =    Order_loader::find(request()->resourceId);
        // $routeto_branch = Routeto_branch::where('dest_branch_id',  $order_loader->branch_rec_id)->first();
        // $waybills = Waybill::where('routeto_branch_id', '=', $routeto_branch->id)->get()
        //     ->pluck('waybill_no', 'id');
        $waybills = Waybill::where('waybill_status', 'loading')->pluck('waybill_no', 'id');
        // $waybills = Waybill::with('car')
        //     ->where('routeto_branch_id', '=', $routeto_branch->id)
        //     ->where('waybill_status', '=', 'loading')
        //     ->get();
        // foreach ($waybills as $waybill) {
        //     $waybillOptions = [
        //         ['branchwaybill' => ['id' => $waybill->id, 'name' => $waybill->waybill_no . '-' . $waybill->car->car_regist]],
        //     ];
        //     $selectOptions = collect($waybillOptions);
        //     $waybillOptions = $selectOptions->pluck('branchwaybill.name', 'branchwaybill.id');
        // }

        return [

            Select::make(__('Waybill'), 'waybill_branch')
                ->options($waybills)
                ->displayUsingLabels()
                ->rules('required')
                ->searchable(),
        ];
        // }
    }
}
