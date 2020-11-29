<?php

namespace App\Nova\Actions;

use App\Models\Product_style;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\Select;

class SetProductStyle extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;
    public $onlyOnIndex = true;

    public function uriKey()
    {
        return 'set-product_style';
    }
    public function name()
    {
        return __('Set product style');
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
            $model->product_style_id = $fields->product_style;
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
        $product_styles = Product_style::all()->pluck('name', 'id');

        return [


            Select::make(__('Product style'), 'product_style')
                ->searchable()
                ->options($product_styles)
                ->displayUsingLabels()


        ];
    }
}
