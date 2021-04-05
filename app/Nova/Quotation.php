<?php

namespace App\Nova;


use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Illuminate\Support\Carbon;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class Quotation extends Resource
{
    //public static $displayInNavigation = false;
    public static $group = "6.งานขนส่งแบบเหมา";
    public static $priority = 4;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Quotation';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'quotation_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'quotation_no'
    ];
    public static function label()
    {
        return 'ใบเสนอราคาเหมาคัน';
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
            Boolean::make('ใช้งาน', 'active')
                ->default(true),
            BelongsTo::make('สาขาที่ทำรายการ', 'branch', 'App\Nova\Branch')
                ->withMeta([
                    'value' => $this->user_id ?? auth()->user()->branch_id,
                    'belongsToId' => $this->user_id ?? auth()->user()->branch_id
                ])
                ->hideFromIndex(),
            Status::make('สถานะ', 'status')
                ->loadingWhen(['Open', 'Edit', 'Confirm'])
                ->failedWhen(['Reject']),

            Text::make('เลขที่ใบเสนอราคา', 'quotation_no')
                ->readonly(true),

            DateTime::make('วันที่', 'quotation_date')
                ->readonly()
                ->default(today())
                ->format('DD/MM/YYYY')
                ->sortable(),

            BelongsTo::make('ลูกค้า', 'customer', 'App\Nova\Customer')
                ->searchable(),
            Select::make('เงื่อนไขการชำระเงิน', 'paymenttype')->options([
                'H' => 'เงินสด',
                'T' => 'เงินโอน',
                'F' => 'วางบิล',
            ])->displayUsingLabels()
                ->hideFromIndex(),
            DateTime::make('กำหนดส่ง', 'duedate')
                ->hideFromIndex()
                ->nullable(),
            DateTime::make('วันที่จัดส่ง', 'delivery_date')
                ->hideFromIndex()
                ->nullable(),

            DateTime::make('ใช้ได้ถึงวันที่', 'expiration_date')
                ->hideFromIndex(),
            Text::make('หมายเหตุ/เงื่อนไขอื่นๆ', 'terms')
                ->hideFromIndex(),
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
            BelongsToMany::make('รายการ', 'charter_prices', 'App\Nova\Charter_price')

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
        return [
            (new Actions\PrintQuotation)->onlyOnDetail(),
        ];
    }
}
