<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class SetCustomerPaymentType extends Action
{
    use InteractsWithQueue, Queueable;

    public function name()
    {
        return 'กำหนดเงื่อนไขการชำระเงิน';
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
            $model->paymenttype = $fields->paymenttype;
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

            Select::make('ประเภท', 'paymenttype')
                ->options([
                    'H' => 'เงินสดต้นทาง',
                    'E' => 'เงินสดปลายทาง',
                    'Y' => 'วางบิล'

                ])->displayUsingLabels(),

        ];
    }
}
