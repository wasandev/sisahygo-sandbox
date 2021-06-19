<?php

namespace App\Nova\Actions;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

class CancelReceipt extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'cancel_receipt';
    }
    public function name()
    {
        return 'ยกเลิกใบเสร็จรับเงิน';
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
            $receipt_date = strtotime($model->receipt_date);
            $receipt_month = date('M', $receipt_date);
            $receipt_year = date('Y', $receipt_date);
            $current_date = strtotime(today());
            $current_month = date('M', $current_date);
            $current_year = date('Y', $current_date);

            if ($model->status || ($receipt_month == $current_month && $receipt_year == $current_year)) {
                $model->status = false;
                $model->save();

                return Action::message('ยกเลิกรายการเรียบร้อยแล้ว');
            }
            return Action::danger('ไม่สามารถยกเลิกรายการนี้ได้');
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
