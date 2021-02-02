<?php

namespace App\Nova\Actions;

use App\Models\Order_status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

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
            if ($model->order_status <> 'cancel' || $model->order_status <> 'delivery' || $model->order_status <> 'completed') {
                $model->order_status = 'cancel';
                $model->save();

                Order_status::create([
                    'order_header_id' => $model->id,
                    'status' => 'cancel',
                    'user_id' => auth()->user()->id,
                ]);
                return Action::message('ยกเลิกรายการเรียบร้อยแล้ว');
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
        return [];
    }
}
