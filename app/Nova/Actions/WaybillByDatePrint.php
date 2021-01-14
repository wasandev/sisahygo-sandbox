<?php

namespace App\Nova\Actions;

use App\Models\Waybill;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;

class WaybillByDatePrint extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'waybill_by_date_print';
    }
    public function name()
    {
        return 'รายงานรถออกประจำวัน';
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

        $waybillController =  new \App\Http\Controllers\WaybillController();
        $path = $waybillController->waybillBydate($fields->from_date, $fields->to_date);

        return Action::openInNewTab(Storage::url('reports/' . 'waybill' . $fields->from_date . '.pdf'));
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Date::make('จากวันที่', 'from_date')->rules('required'),
            Date::make('ถึงวันที่', 'to_date')->rules('required'),
        ];
    }
}
