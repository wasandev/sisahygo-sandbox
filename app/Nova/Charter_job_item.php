<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Select;
use App\Models\Address;
use App\Models\Charter_job;
use App\Models\Charter_route;
use App\Models\Charter_price;
use App\Models\Branch_area;
use App\Models\Customer;
use App\Rules\Truckweight;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class Charter_job_item extends Resource
{
    public static $displayInNavigation = false;
    public static $group = "6.งานขนส่งแบบเหมา";
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Charter_job_item';

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
        return 'จุดรับส่ง-รายการสินค้า';
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
            BelongsTo::make('ใบรับงานขนส่งเหมาคัน', 'charter_job', 'App\Nova\Charter_job')
                ->hideFromIndex(),

            BelongsTo::make('จุดรับสินค้าของลูกค้า', 'from_address', 'App\Nova\Address')
                //->searchable()
                ->withSubtitles()
                ->showCreateRelationButton(),
            BelongsTo::make('จุดส่งสินค้าของลูกค้า', 'to_address', 'App\Nova\Address')
                //->searchable()
                ->withSubtitles()
                ->showCreateRelationButton(),

            BelongsTo::make('สินค้า', 'product', 'App\Nova\Product')
                ->rules('required')
                ->searchable()
                ->showCreateRelationButton(),
            Number::make('จำนวนสินค้า', 'amount')
                ->step('0.01')
                ->rules('required'),
            BelongsTo::make('หน่วยนับ', 'unit', 'App\Nova\Unit')
                ->rules('required')
                ->showCreateRelationButton(),
            Number::make('น้ำหนักสินค้ารวม(กก.)', 'total_weight')
                ->hideFromIndex(),
            Currency::make('มูลค่าสินค้ารวม(บาท)', 'productvalue')

                ->hideFromIndex(),
            DateTime::make('วันที่ไปรับสินค้า', 'pickup_date')

                ->rules('required')
                ->hideFromIndex(),
            DateTime::make('วันที่กำหนดส่งสินค้า', 'delivery_date')
                ->rules('required')
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
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/' . $request->input('viaResource') . '/' . $request->input('viaResourceId');
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/' . $request->input('viaResource') . '/' . $request->input('viaResourceId');
    }
}
