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

        return [
            ID::make()->sortable(),
            BelongsTo::make('ใบเสนอราคา', 'quotation', 'App\Nova\Quotation'),
            BelongsTo::make('จุดรับสินค้า', 'from_address', 'App\Nova\Address')
                ->showCreateRelationButton(),
            BelongsTo::make('จุดส่งสินค้า', 'to_address', 'App\Nova\Address')
                ->showCreateRelationButton(),
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
