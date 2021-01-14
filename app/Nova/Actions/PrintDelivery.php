<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;


class PrintDelivery extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'print-delivery';
    }
    public function name()
    {
        return __('Print Delivery');
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

            $deliveryController =  new \App\Http\Controllers\DeliveryController();
            $path = $deliveryController->makePDF($model->id);

            return Action::openInNewTab(Storage::url('documents/' . $model->delivery_no . '.pdf'));
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
