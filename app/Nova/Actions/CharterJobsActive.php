<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class CharterJobsActive extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'charter_jobs_active';
    }
    public function name()
    {
        return __('Charter Jobs Active');
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
            $hasitem = count($model->charter_job_items);
            if ($model->status <> 'New') {
                return Action::danger('ไม่สามารถยืนยันรายการที่-ยืนยันหรือยกเลิก-ไปแล้วได้');
            } elseif ($hasitem) {
                $model->status = 'Confirmed';
                $model->save();
                return Action::message('ยืนยันรายการเรียบร้อยแล้ว');
            }

            return Action::danger('ไม่สามารถยืนยันรายการได้ ->ยังไม่มีรายการจุดรับ-ส่งสินค้า!');
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
