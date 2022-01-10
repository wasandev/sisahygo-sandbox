<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Charter_price extends Resource
{
    //public static $displayInNavigation = false;
    public static $group = "6.งานขนส่งแบบเหมา";
    public static $priority = 3;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Charter_price';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    //public static $title = 'charter_route->name';

    public function title()
    {
        return $this->charter_route->name . '->' . $this->cartype->name  . ' ค่าขนส่ง/เที่ยว  = ' . $this->price;
    }
    // public function subtitle()
    // {
    //     return $this->cartype->name . '->' . $this->carstyle->name . '->' . $this->price;
    // }
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
        return 'ราคาขนส่งเหมาคัน';
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
            Boolean::make(__('Status'), 'status')
                ->sortable()
                ->default(true),
            BelongsTo::make('เส้นทางขนส่ง', 'charter_route', 'App\Nova\Charter_route')
                ->sortable()
                ->rules('required')
                ->showCreateRelationButton(),
            BelongsTo::make('ประเภทรถ', 'cartype', 'App\Nova\Cartype')
                ->sortable()
                ->rules('required')
                ->showCreateRelationButton(),
            BelongsTo::make('ลักษณะรถ', 'carstyle', 'App\Nova\Carstyle')
                ->sortable()
                ->rules('required')
                ->showCreateRelationButton()
                ->hideFromIndex(),

            Currency::make('ค่าขนส่ง/เที่ยว', 'price')
                ->sortable()
                ->rules('required'),

            Number::make('จำนวนจุดรับส่งไม่เกิน', 'pickuppoint')
                ->step('0.01')
                ->withMeta(['value' => 2])
                ->hideFromIndex(),

            Currency::make('จุดรับส่งเกินที่กำหนดคิดค่าบริการจุดละ', 'overpointcharge')
                ->sortable()
                ->rules('required')
                ->hideFromIndex()
                ->withMeta(['value' => 500]),
            Currency::make('ค่าจ้างรถ(กรณีรถร่วม)', 'car_charge')
                ->sortable()
                ->rules('required')
                ->hideFromIndex(),
            Currency::make('ค่าเที่ยวคนขับ(กรณีรถบริษัท)', 'driver_charge')
                ->sortable()
                ->rules('required')
                ->hideFromIndex(),
            Currency::make('ค่าเชื้อเพลิงที่ใช้', 'fuel_cost')
                ->sortable()
                ->rules('required')
                ->hideFromIndex(),
            Number::make('จำนวนเชื้อเพลิงที่ใช้(ลิตร)', 'fuel_amount')
                ->step('0.01')
                ->sortable()
                ->rules('required')
                ->hideFromIndex(),
            Number::make('ระยะเวลาขนส่ง(ชม.)', 'timespent')
                ->onlyOnDetail()
                ->step('0.01'),
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->OnlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsToMany::make('ใบเสนอราคา', 'quotations', 'App\Nova\Quotation')
                ->withSubtitles()
                ->fields(function () {
                    return [
                        Select::make(__('Product'), 'product_id')->options(\App\Models\Product::pluck('name', 'id')->toArray())->displayUsingLabels()
                            ->searchable(),
                        Number::make('จำนวนสินค้า', 'product_amount'),
                        Select::make(__('Unit'), 'unit_id')->options(\App\Models\Unit::pluck('name', 'id')->toArray())->displayUsingLabels()
                            ->searchable(),
                        Number::make('น้ำหนักสินค้ารวม(กก.)', 'product_weight'),
                        Text::make(__('Description'), 'description')
                            ->nullable()
                            ->hideFromIndex(),
                        Number::make('จำนวนเที่ยว', 'charter_amount'),

                    ];
                }),

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
        return [
            new Filters\CarType,
        ];
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
