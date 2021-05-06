<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class PrintWaybillPdf extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'print-waybill-pdf';
    }
    public function name()
    {
        return 'บันทึกเป็นไฟล์ PDF';
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
            if ($model->waybill_status == 'loading') {
                return Action::danger('ไม่สามารถบันทึกใบกำกับสินค้าที่ยังไม่ยืนยันรายการ');
            }
            $waybillController =  new \App\Http\Controllers\WaybillController();
            $path = $waybillController->makePDF($model->id);

            return Action::openInNewTab(Storage::url('documents/' . $model->waybill_no . '.pdf'));
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
