<?php

namespace App\Nova\Actions;


use App\Models\Branch_area;
use App\Models\Branch;
use App\Models\District;
use App\Models\District_pricezone;
use App\Models\Pricezone;
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
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\BooleanGroup;
use OptimistDigital\MultiselectField\Multiselect;

class AddProductServiceNewPriceZone extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;
    //public $showOnTableRow = true;

    public function uriKey()
    {
        return 'Add Product Service New Price Zone';
    }
    public function name()
    {
        return __('Add Product Service New Price Zone');
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

            $pricezone = $fields->zone;
            $districts = District_pricezone::where('pricezone_id', $pricezone)->get();
            if ($fields->product_unit) {
                $uses_unit = $model->unit_id;
            } else {
                $uses_unit = $fields->unit;
            }
            foreach ($districts as $districtinzone) {

                $district = District::find($districtinzone->district_id);
                Productservice_newprice::updateOrCreate(
                    [
                        'product_id' => $model->id,
                        'from_branch_id' => $fields->from_branch_id,
                        'district' => $district->name,
                        'province' => $district->province->name,
                        'unit_id' => $uses_unit
                    ],
                    [
                        'price' => $fields->item_price

                    ]
                );
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
        $pricezones  = Pricezone::all()->pluck('name', 'id');
        $units = Unit::all()->pluck('name', 'id');


        return [

            Select::make(__('From branch'), 'from_branch_id')
                ->options($branches)
                ->displayUsingLabels()
                ->rules('required'),
            Select::make(__('เลือกโซนราคา'), 'zone')
                ->options($pricezones)
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
                ->step('0.01')
                ->rules('required'),

        ];
    }
}
