<?php

namespace App\Nova\Actions;

use App\Models\Branch_area;
use App\Models\Branch;
use App\Models\Unit;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Customer_product_price;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Boolean;
// use Illuminate\Database\Eloquent\Builder;
// use Epartment\NovaDependencyContainer\HasDependencies;
// use Epartment\NovaDependencyContainer\NovaDependencyContainer;


class AddCustomerProductPrice extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;
    //public $onlyOnIndex = true;

    public function uriKey()
    {
        return 'Add Customer product shipping cost';
    }
    public function name()
    {
        return __('Add Customer product shipping cost');
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


            $branch_areas = Branch_area::where('branch_id', $fields->to_branch_id)->get();
            $products = Customer::find($model->id)->product()->get();
            if (count($products) == 0) {
                return Action::danger('ไม่มีสินค้าของลูกค้ารายนี้ ให้ไปกำหนดสินค้าของลูกค้าก่อน');
            }
            foreach ($products as $product) {
                if ($fields->active) {
                    $curproduct = Product::find($product->id);
                    $uses_unit = $curproduct->unit_id;
                } else {
                    $uses_unit = $fields->unit;
                }
                foreach ($branch_areas as $branch_area) {

                    Customer_product_price::updateOrCreate([
                        'customer_id' => $model->id,
                        'product_id' => $product->id,
                        'from_branch_id' => $fields->from_branch_id,
                        'district' => $branch_area->district,
                        'province' => $branch_area->province,
                        'price' => $fields->item_price,
                        'unit_id' => $uses_unit,
                    ]);
                }
            }
        }
        return Action::message('สร้างราคาขนส่งสินค้าของลูกค้าสำเร็จแล้ว');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {

        //$products = Product::all()->pluck('name', 'id');
        $branches  = Branch::all()->pluck('name', 'id');
        $units = Unit::all()->pluck('name', 'id');

        return [

            // Select::make('เลือกสินค้า', 'product_id')
            //     ->options($products)
            //     ->displayUsingLabels(),
            Select::make(__('From branch'), 'from_branch_id')
                ->options($branches)
                ->displayUsingLabels(),
            Select::make(__('To branch'), 'to_branch_id')
                ->options($branches)
                ->displayUsingLabels(),
            Boolean::make(__('Used product unit'), 'active'),

            Select::make(__('Unit'), 'unit')
                ->options($units)
                ->displayUsingLabels(),
            Currency::make(__('Shipping cost'), 'item_price'),



        ];
    }
}
