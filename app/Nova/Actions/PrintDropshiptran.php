<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class PrintDropshiptran extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'print-dropship_tran';
    }
    public function name()
    {
        return 'พิมพ์ใบจัดส่งสินค้าจากตัวแทน';
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

            $dropshipController =  new \App\Http\Controllers\DropshipController();
            $path = $dropshipController->makePDF($model->id);

            return Action::openInNewTab(Storage::url('documents/' . $model->dropship_tran_no . '.pdf'));
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
