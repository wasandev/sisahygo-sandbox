<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class OrderConfirmed extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'order_confirmed';
    }
    public function name()
    {
        return __('Order Confirmed');
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
            $hasitem = count($model->order_details);
            if ($model->order_status <> 'New') {
                return Action::danger('ไม่สามารถยืนยันรายการที่ ยืนยัน/ยกเลิก ไปแล้วได้');
            } elseif ($hasitem) {
                $model->order_status = 'Confirmed';
                $model->save();
                return Action::message('ยืนยันรายการเรียบร้อยแล้ว');
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
        return [];
    }
}
