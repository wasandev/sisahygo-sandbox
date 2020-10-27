<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class CharterJobCancel extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'charter_job_cancel';
    }
    public function name()
    {
        return __('Charter Job Cancel');
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
            if ($model->status <> 'Cancel') {
                $model->status = 'Cancel';
                $model->save();
                return Action::message('ยกเลิกรายการเรียบร้อยแล้ว');
            }
            return Action::danger('รายการนี้ยกเลิกไปแล้ว');
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
