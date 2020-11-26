<?php

namespace App\Nova\Actions;


use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use App\Models\Waybill;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Text;

class OrderLoaded extends Action
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
            $hasitem = count($model->order_details);
            //$order_amount = $model->order_details->price->sum();

            if ($model->order_status == 'loaded') {
                return Action::danger('รายการนี้จัดขึ้นแล้ว');
            } elseif ($hasitem) {

                $model->order_status = 'loaded';
                $model->waybill_id = $fields->waybill;
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
        $waybills = Waybill::all()->pluck('waybill_no', 'id');

        return [
            Text::make('Test')->default(function ($request) {
                //$resourceId = $request->route('resourceId');
                return $request->resourceId;
            }),

            Select::make(__('Waybill'), 'waybill')
                ->options($waybills)
                ->displayUsingLabels(),
        ];
    }
}
