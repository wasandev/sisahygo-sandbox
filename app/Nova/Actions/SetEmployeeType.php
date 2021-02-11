<?php

namespace App\Nova\Actions;


use App\Models\Branch;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\Select;

class SetEmployeeType extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;
    public $onlyOnIndex = true;

    public function uriKey()
    {
        return 'set-employee-type';
    }
    public function name()
    {
        return 'กำหนดประเภทพนักงาน';
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
            $model->type = $fields->type;
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


        return [


            Select::make('สาขา', 'type')
                ->options([
                    'ผู้บริหาร' => 'ผู้บริหาร',
                    'พนักงานบริษัท' => 'พนักงานบริษัท',
                    'พนักงานบริษัทร่วม' => 'พนักงานบริษัทร่วม',
                    'แรงงาน' => 'แรงงาน',
                    'พนักงานขับรถบริษัท' => 'พนักงานขับรถบริษัท',
                    'พนักงานขับรถร่วม' => 'พนักงานขับรถร่วม',
                ])
                ->displayUsingLabels()
                ->searchable()


        ];
    }
}
