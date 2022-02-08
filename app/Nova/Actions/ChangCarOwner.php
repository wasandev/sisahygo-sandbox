<?php

namespace App\Nova\Actions;

use App\Models\Car_balance;
use App\Models\Carpayment;
use App\Models\Carreceive;
use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;

class ChangCarOwner extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'chang-car-owner';
    }
    public function name()
    {
        return 'เปลี่ยนเจ้าของรถตามวันที่กำหนด';
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
            $model->vendor_id = $fields->owner;
            $model->save();
            //Carpayment
            Carpayment::where('car_id', $model->id)
                ->whereDate('payment_date', '>=', $fields->from)
                ->whereDate('payment_date', '<=', $fields->to)
                ->update(['vendor_id' => $fields->owner]);
            //Carpayment
            Carreceive::where('car_id', $model->id)
                ->whereDate('receive_date', '>=', $fields->from)
                ->whereDate('receive_date', '<=', $fields->to)
                ->update(['vendor_id' => $fields->owner]);

            //car_balance
            Car_balance::where('car_id', $model->id)
                ->whereDate('cardoc_date', '>=', $fields->from)
                ->whereDate('cardoc_date', '<=', $fields->to)
                ->update(['vendor_id' => $fields->owner]);
        }
        return Action::message('เปลี่ยนเจ้าของรถเรียบร้อยแล้ว');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $owners = Vendor::all()->pluck('name', 'id');
        return [
            Select::make('เจ้าของรถใหม่', 'owner')
                ->options($owners)
                ->displayUsingLabels()
                ->searchable()
                ->rules('required'),
            Date::make('วันที่เริ่มต้น', 'from')
                ->rules('required'),
            Date::make('วันที่สิ้นสุด', 'to')
                ->rules('required')
        ];
    }
}
