<?php

namespace App\Nova\Actions;


use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

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
            $hasitem = count($model->order_details);
            //$order_amount = $model->order_details->price->sum();

            if ($model->order_status <> 'checking') {
                return Action::danger('ไม่สามารถยืนยันรายการที่ ยืนยันไปแล้วได้');
            } elseif ($hasitem) {


                $model->order_status = 'new';

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
