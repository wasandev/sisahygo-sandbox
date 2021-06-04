<?php

namespace App\Nova\Actions;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\DateTime;

class WaybillArrival extends Action
{
    use InteractsWithQueue, Queueable;

    public function __construct($model = null)
    {
        $this->model = $model;
    }

    public function uriKey()
    {
        return 'waybill_arrival';
    }
    public function name()
    {
        return __('Waybill arrival');
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

            if ($model->waybill_status <> 'in transit') {
                return Action::danger('ไม่สามารถกำหนดรถถึงสาขาสำหรับใบกำกับรายการนี้ได้');
            }

            $model->arrivaled_at =   $fields->arrivaled_at;
            //$model->arrivaled_at = Carbon::now()->toDateTimeString();
            $model->waybill_status = 'arrival';
            $model->save();
            return Action::push('/resources/branchrec_waybills/');
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            DateTime::make('วัน-เวลาถึงสาขาปลายทางจริง', 'arrivaled_at')
                ->format('DD/MM/YYYY HH:mm')
                ->rules('required'),

        ];
    }
}
