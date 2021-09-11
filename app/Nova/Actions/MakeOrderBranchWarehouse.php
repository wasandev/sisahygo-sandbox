<?php

namespace App\Nova\Actions;

use App\Models\Order_status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class MakeOrderBranchWarehouse extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'make-order-branch-warehouse';
    }
    public function name()
    {
        return 'สร้างรายการจัดสินค้าลงไว้คลังสาขา';
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

        $select_orders = $models->filter(function ($item) {
            return data_get($item, 'order_status') == 'arrival';
        });

        $verify_models = $select_orders->all();


        if (isset($verify_models)) {

            foreach ($verify_models as $model) {

                $model->order_status = 'branch warehouse';
                $model->save();
                Order_status::create([
                    'order_header_id' => $model->id,
                    'status' => 'branch warehouse',
                    'user_id' => auth()->user()->id,
                ]);
            }
            return Action::message('ทำรายใบรับส่งลงไว้สาขาแล้ว');
        }
        return Action::danger('รายการใบรับส่งที่เลือก ไม่สามารถทำรายการได้ เลือกได้เฉพาะสถานะ -arrival- เท่านั้น');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()

    {
        return [];
    }
}
