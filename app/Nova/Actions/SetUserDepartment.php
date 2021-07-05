<?php

namespace App\Nova\Actions;


use App\Models\Branch;
use App\Models\Department;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\Select;

class SetUserDepartment extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;
    public $onlyOnIndex = true;

    public function uriKey()
    {
        return 'set-user-departmant';
    }
    public function name()
    {
        return 'กำหนดฝ่าย/แผนกให้ผู้ใช้';
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
            $model->department_id = $fields->department;
            $model->save();
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $departments = Department::all()->pluck('name', 'id');

        return [


            Select::make('ฝ่าย/แผนก', 'department')
                ->options($departments)
                ->searchable()
                ->displayUsingLabels()


        ];
    }
}
