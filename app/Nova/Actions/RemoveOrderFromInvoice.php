<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class RemoveOrderFromInvoice extends Action
{
    use InteractsWithQueue, Queueable;
    public function uriKey()
    {
        return 'remove_order_from_invoice';
    }
    public function name()
    {
        return 'นำใบรับส่งออกจากใบแจ้งหนี้';
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
            $model->invoice_id = null;
            $model->save();
        }
        return Action::message('นำใบรับส่งออกแล้ว');
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
