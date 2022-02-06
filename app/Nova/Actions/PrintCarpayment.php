<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class PrintCarpayment extends Action
{
    use InteractsWithQueue, Queueable;
    public $withoutActionEvents = true;
    public function uriKey()
    {
        return 'print-carpayment';
    }
    public function name()
    {
        return 'พิมพ์ใบสำคัญจ่าย';
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
        $selectitems = array();
        foreach ($models as $model) {

            $selectitems[] =  $model->id;
        }

        $items = implode(",", $selectitems);
        return Action::openInNewTab('/car/carpaymentprint/' . $items);

        //return Action::push('/resources/carpayments');
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
