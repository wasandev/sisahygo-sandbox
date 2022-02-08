<?php

namespace App\Nova;

use App\Nova\Filters\CarbalanceByCar;
use App\Nova\Filters\CarbalanceByOwner;
use App\Nova\Filters\CarbalanceFromDate;
use App\Nova\Filters\CarbalanceToDate;
use App\Nova\Lenses\cars\CarcardReport;
use App\Nova\Lenses\cars\CarsummaryReport;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Car_balance extends Resource
{
    public static $group = "3.งานด้านรถบรรทุก";
    public static $priority = 7;
    public static $trafficCop = false;
    public static $with = ['car',  'vendor', 'user'];
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Car_balance::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'docno';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'docno'
    ];

    public static function label()
    {
        return __('Car Balance');
    }
    public static $searchRelations = [
        'car' => ['car_regist'],
        'vendor' => ['name']
    ];
    public static $globalSearchRelations = [
        'car' => ['car_regist'],

    ];
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('Car'), 'car', 'App\Nova\Car')
                ->sortable(),
            BelongsTo::make('เจ้าของรถ', 'vendor', 'App\Nova\Vendor')
                ->sortable()
                ->exceptOnForms(),

            Select::make('ประเภท', 'doctype')
                ->options([
                    'R' => 'รับ',
                    'P' => 'จ่าย'
                ])
                ->sortable()
                ->displayUsingLabels(),
            Date::make('วันที่', 'cardoc_date')->sortable(),
            Text::make('เลขที่เอกสาร', 'docno')->sortable(),


            BelongsTo::make('ใบกำกับ', 'waybill', 'App\Nova\Waybill')
                ->sortable()->hideFromIndex(),
            BelongsTo::make('ใบจ่ายเงิน', 'carpayment', 'App\Nova\Carpayment')
                ->sortable()->hideFromIndex(),
            BelongsTo::make('ใบรับเงิน', 'carreceive', 'App\Nova\Carreceive')
                ->sortable()->hideFromIndex(),
            Currency::make('จำนวนเงิน', 'amount'),
            Text::make('รายละเอียด', 'description'),

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
            new CarbalanceByOwner,
            new CarbalanceByCar,
            new CarbalanceFromDate,
            new CarbalanceToDate,
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
    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->branch->type == 'partner') {

            return   $query->where('vendor_id', $request->user()->branch->vendor_id);
        }
        return $query;
    }
}
