<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;

class SetDeliveryMile extends Action
{
    use InteractsWithQueue, Queueable;
    
    public function __construct($model = null)
    {
        $this->model = $model;
    }

    public function uriKey()
    {
        return 'set_delivery_mile';
    }
    public function name()
    {
        return 'บันทึกระยะทางการจัดส่ง';
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
            if (($fields->mile_start_number > $fields->mile_end_number) || ($fields->mile_end_number - $fields->mile_start_number > 500) ) {
                return Action::danger('ข้อมูลไม่ถูกต้อง กรุณาตรวจสอบ');
            }else {
                $model->mile_start_number =   $fields->mile_start_number;
                $model->mile_end_number = $fields->mile_end_number;
                if($fields->delivery_mile > 0){
                    $model->delivery_mile = $fields->delivery_mile;
                }else{
                    $model->delivery_mile = $fields->mile_end_number -  $fields->mile_start_number;
                }
                
                $model->save();

            }
            
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

            Number::make('เลขไมล์เริ่มต้น','mile_start_number'),
            Number::make('เลขไมล์สิ้นสุด','mile_end_number'),           
            Number::make('รวมระยะทางขนส่ง', 'delivery_mile'),
        ];
    }
}
