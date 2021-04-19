<?php

namespace App\Nova\Actions;

use App\Models\Charter_job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class CharterJobsActive extends Action
{
    use InteractsWithQueue, Queueable;

    public function __construct($model = null)
    {
        $this->model = $model;
    }
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
                $model->car_id = $fields->car_id;
                $model->driver_id = $fields->driver_id;
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
        if ($this->model) {
            $charter_job = Charter_job::find($this->model);
            $cartype = $charter_job->charter_price->cartype_id;
            return [
                Select::make(__('Car'), 'car_id')->options(\App\Models\Car::where('cartype_id', $cartype)->get()->pluck('car_regist', 'id')->toArray())->displayUsingLabels()
                    ->searchable()
                    ->rules('required'),
                Select::make(__('Driver'), 'driver_id')->options(\App\Models\Employee::whereIn('type', ['พนักงานขับรถบริษัท', 'พนักงานขับรถร่วม'])->get()->pluck('name', 'id')->toArray())->displayUsingLabels()
                    ->searchable()
                    ->rules('required'),

            ];
        }
        return [
            Select::make(__('Car'), 'car_id')->options(\App\Models\Car::pluck('car_regist', 'id')->toArray())->displayUsingLabels()
                ->searchable(),
            Select::make(__('Driver'), 'driver_id')->options(\App\Models\Employee::pluck('name', 'id')->toArray())->displayUsingLabels()
                ->searchable(),

        ];
    }
}
