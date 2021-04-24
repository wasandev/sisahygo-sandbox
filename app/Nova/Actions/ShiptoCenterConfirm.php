<?php

namespace App\Nova\Actions;

use App\Models\Order_dropship;
use App\Models\Order_status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ShiptoCenterConfirm extends Action
{
    use InteractsWithQueue, Queueable;
    public function uriKey()
    {
        return 'shipto-center-confirm';
    }
    public function name()
    {
        return 'ยืนยันการตรวจรับใบจัดส่งสินค้าจากตัวแทน';
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
            $hasitem = count($model->order_dropships);
            //$order_amount = $model->order_details->price->sum();

            if ($model->status) {
                return Action::danger('ไม่สามารถยืนยันรายการที่ ยืนยันไปแล้วได้');
            } elseif ($hasitem) {

                $model->status = true;
                $model->save();

                foreach ($model->order_dropships as $orders) {
                    Order_dropship::where('id', $orders->id)
                        ->update(['shipto_center' => '2']);

                    Order_status::create([
                        'order_header_id' => $orders->id,
                        'status' => 'arrival at center',
                        'user_id' => auth()->user()->id,
                    ]);
                }

                return Action::push('/resources/dropship_trans');
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
        return [];
    }
}
