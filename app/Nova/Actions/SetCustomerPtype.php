<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class SetCustomerPtype extends Action
{
    use InteractsWithQueue, Queueable;

    public function name()
    {
        return 'กำหนดบุคคลธรรมดา/นิติบุคคล';
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
            $model->type = $fields->ptype;
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

            Select::make('ประเภท', 'ptype')
                ->options([
                    'person' => 'บุคคลธรรมดา',
                    'company' => 'นิติบุคคล',

                ])->displayUsingLabels(),

        ];
    }
}
