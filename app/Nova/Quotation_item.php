<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Quotation;
use App\Models\Address;

class Quotation_item extends Resource
{
    public static $displayInNavigation = false;
    public static $group = "6.งานขนส่งแบบเหมา";
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Quotation_item';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function label()
    {
        return 'รายการในใบเสนอราคา';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        if (($request->editMode == "create" || $request->editMode == "update") && !empty($request->viaResource) && !empty($request->viaResourceId) && !empty($request->viaRelationship)) {

            $quotation = Quotation::find($request->viaResourceId);
            $address = Address::where('customer_id', $quotation->customer_id)->pluck('name', 'id');
            return [
                ID::make()->sortable(),
                Select::make('เลขที่ใบเสนอราคา', 'quotation_id')
                    ->options($quotation)
                    ->options([$quotation->id => $quotation->quotation_no])
                    ->displayUsingLabels()
                    ->withMeta(['value' => $quotation->id])
                    ->hideWhenUpdating()
                    ->readonly(true),
                Select::make('จุดรับสินค้า', 'from_address_id')
                    ->options($address)
                    ->onlyOnForms(),
                Select::make('จุดส่งสินค้า', 'to_address_id')
                    ->options($address)
                    ->onlyOnForms(),
                BelongsTo::make('ประเภทรถ', 'cartype', 'App\Nova\Cartype'),
                BelongsTo::make('ลักษณะรถ', 'carstyle', 'App\Nova\Carstyle')

                    ->hideFromIndex(),
                Belongsto::make('สินค้า', 'product', 'App\Nova\Product')

                    ->searchable(),
                Currency::make('จำนวน', 'number'),
                BelongsTo::make('หน่วยนับสินค้า', 'unit', 'App\Nova\Unit')

                    ->searchable(),
                Currency::make('น้ำหนักสินค้ารวม(กก.)', 'total_weight'),
                Currency::make('ค่าขนส่ง', 'amount'),
                DateTime::make('เวลารับสินค้า', 'pickup_date')

                    ->hideFromIndex(),
                DateTime::make('เวลาส่งสินค้า', 'delivery_date')

                    ->hideFromIndex(),

            ];
        }
        return [
            ID::make()->sortable(),
            BelongsTo::make('เลขที่ใบเสนอราคา', 'quotation', 'App\Nova\Quotation'),
            BelongsTo::make('จุดรับสินค้า', 'from_address', 'App\Nova\Address'),
            BelongsTo::make('จุดส่งสินค้า', 'to_address', 'App\Nova\Address'),
            BelongsTo::make('ประเภทรถ', 'cartype', 'App\Nova\Cartype'),
            BelongsTo::make('ลักษณะรถ', 'carstyle', 'App\Nova\Carstyle')

                ->hideFromIndex(),
            Belongsto::make('สินค้า', 'product', 'App\Nova\Product'),
            Currency::make('จำนวน', 'number'),
            BelongsTo::make('หน่วยนับสินค้า', 'unit', 'App\Nova\Unit'),
            Currency::make('น้ำหนักสินค้ารวม(กก.)', 'total_weight'),
            Currency::make('ค่าขนส่ง', 'amount'),
            DateTime::make('เวลารับสินค้า', 'pickup_date')

                ->hideFromIndex(),
            DateTime::make('เวลาส่งสินค้า', 'delivery_date')

                ->hideFromIndex(),



        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
