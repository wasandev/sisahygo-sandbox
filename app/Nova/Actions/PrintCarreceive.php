<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class PrintCarreceive extends Action
{
    use InteractsWithQueue, Queueable;
    public $withoutActionEvents = true;
    public function uriKey()
    {
        return 'print-carreceive';
    }
    public function name()
    {
        return 'พิมพ์ใบสำคัญรับ';
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


            return Action::openInNewTab('/car/carreceiveprint/' . $model->id);
        }
        return Action::push('/resources/carreceives');
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
