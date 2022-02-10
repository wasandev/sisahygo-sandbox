<?php

namespace App\Nova\Actions;

use App\Models\Branch_area;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Productservice_newprice;
use App\Models\Unit;
use App\Models\Productservice_price;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;

class AddProductServiceNewPriceStyle extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;
    //public $showOnTableRow = true;

    public function uriKey()
    {
        return 'add-product-service-new-price-style';
    }
    public function name()
    {
        return __('Add Product Service New Price Style');
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

            $products =  Product::where('product_style_id', $model->id)->get();
            $branch_areas = Branch_area::where('branch_id', $fields->to_branch_id)->get();


            foreach ($products as $product) {

                if ($fields->product_unit) {
                    $uses_unit = $product->unit_id;
                } else {
                    $uses_unit = $fields->unit;
                }
                foreach ($branch_areas as $branch_area) {

                    Productservice_newprice::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'from_branch_id' => $fields->from_branch_id,
                            'district' => $branch_area->district,
                            'province' => $branch_area->province
                        ],
                        [
                            'price' => $fields->item_price,
                            'unit_id' => $uses_unit
                        ]
                    );
                }
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {


        $branches  = Branch::all()->pluck('name', 'id');

        $units = Unit::all()->pluck('name', 'id');

        return [

            Select::make(__('From branch'), 'from_branch_id')
                ->options($branches)
                ->displayUsingLabels()
                ->rules('required'),
            Select::make(__('To branch'), 'to_branch_id')
                ->options($branches)
                ->displayUsingLabels()
                ->rules('required'),

            Boolean::make(__('Used product unit'), 'product_unit')
                ->default(true),
            NovaDependencyContainer::make([
                Select::make(__('Unit'), 'unit')
                    ->options($units)
                    ->displayUsingLabels()
                    ->searchable(),
            ])->dependsOn('product_unit', false),
            Number::make(__('Shipping cost'), 'item_price')
                ->step('0.01'),



        ];
    }
}
