<?php

namespace App\Nova\Actions;

use App\Models\Order_loader;
use App\Models\Order_status;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class WaybillTransporting extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'waybill_transporting';
    }
    public function name()
    {
        return __('Waybill transporting');
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

            if ($model->waybill_status <> 'confirmed') {
                return Action::danger('ใบกำกับรายการนี้ยังไม่ทำรายการยืนยัน');
            } elseif ($hasitem) {

                foreach ($model->order_loaders as $orders) {
                    Order_loader::where('id', $orders->id)
                        ->update(['order_status' => 'in transit']);
                    Order_status::create([
                        'order_header_id' => $orders->id,
                        'status' => 'in transit',
                        'user_id' => auth()->user()->id,
                    ]);
                }
                $model->departure_at = Carbon::now()->toDateTimeString();
                $model->waybill_status = 'in transit';
                $model->save();
                //return Action::message('กำหนดรถออกเดินทางแล้ว');
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

        return [];
    }
}
