<?php

namespace App\Nova\Actions;

use App\Models\Businesstype;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class SetCustomerType extends Action
{
    use InteractsWithQueue, Queueable;

    public function name()
    {
        return 'กำหนดประเภทธุรกิจ';
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
            $model->businesstype_id = $fields->businesstype;
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
        $businesstype = Businesstype::all()->pluck('name', 'id');

        return [

            Select::make('ประเภทธุรกิจ', 'businesstype')
                ->options($businesstype)
                ->displayUsingLabels()
                ->searchable()

        ];
    }
}
