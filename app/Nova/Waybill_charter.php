<?php

namespace App\Nova;


use App\Nova\Actions\WaybillConfirmed;
use App\Nova\Filters\RouteToBranch;
use App\Nova\Filters\ToBranch;
use App\Nova\Filters\WaybillDateFilter;
use App\Nova\Filters\WaybillFromDate;
use App\Nova\Filters\WaybillToDate;
use App\Nova\Lenses\WaybillConfirmedPerDay;
use App\Nova\Metrics\WaybillAmount;
use App\Nova\Metrics\WaybillIncome;
use App\Nova\Metrics\WaybillIncomePerDay;
use App\Nova\Metrics\WaybillLoading;
use App\Nova\Metrics\WaybillPayable;
use App\Nova\Metrics\WaybillsPerDay;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Requests\ActionRequest;


class Waybill_charter extends Resource
{
    use HasDependencies;
    public static $group = "6.งานขนส่งแบบเหมา";
    public static $priority = 6;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Waybill_charter::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    //public static $title = 'waybill_no';

    public function title()
    {
        return $this->waybill_no;
    }
    public function subtitle()
    {
        return $this->car->car_regist;
    }


    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'waybill_no',
    ];

    public static $searchRelations = [
        'car' => ['car_regist'],
    ];
    public static $globalSearchRelations = [
        'car' => ['car_regist'],
    ];

    public static function label()
    {
        return 'ใบกำกับสินค้าเหมาคัน';
    }
    public static function singularLabel()
    {
        return 'ใบกำกับเหมาคัน';
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
            ID::make(__('ID'), 'id')->sortable(),
            Status::make(__('Status'), 'waybill_status')
                ->loadingWhen(['loading'])
                ->failedWhen(['cancel'])
                ->exceptOnForms()
                ->sortable(),
            Text::make(__('Waybill no'), 'waybill_no')
                ->readonly()
                ->sortable(),
            Date::make(__('Waybill date'), 'waybill_date')
                ->readonly()
                ->default(today())
                ->format('DD/MM/YYYY')
                ->sortable(),


            BelongsTo::make(__('Charter route'), 'charter_route', 'App\Nova\Charter_route')
                ->nullable()
                ->showCreateRelationButton(),
            BelongsTo::make(__('Car regist'), 'car', 'App\Nova\Car')
                ->searchable()
                ->withSubtitles()
                ->sortable(),


            Currency::make('ค่าขนส่งรวม', 'waybill_amount'),
            Currency::make('ค่าบรรทุก', 'waybill_payable')
                ->hideWhenCreating()
                ->hideFromIndex(),
            Currency::make('รายได้บริษัท', 'waybill_income')
                ->onlyOnDetail(),


            BelongsTo::make(__('Driver'), 'driver', 'App\Nova\Employee')
                ->searchable()
                ->hideFromIndex(),

            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            HasMany::make('ใบรับส่งเหมาคัน', 'order_charters', 'App\Nova\Order_charter'),
            //HasMany::make(__('Waybill status'), 'waybill_statuses', 'App\Nova\Waybill_status'),
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

            // (new WaybillFromDate()),
            // (new WaybillToDate()),

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
        return [
            // new  WaybillConfirmedPerDay(),

        ];
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

            // (new Actions\WaybillConfirmed($request->resourceId))

            //     ->onlyOnDetail()
            //     ->confirmText('ต้องการยืนยันใบกำกับสินค้ารายการนี้?')
            //     ->confirmButtonText('ยืนยัน')
            //     ->cancelButtonText("ไม่ยืนยัน")
            //     ->canRun(function ($request) {
            //         return $request->user()->hasPermissionTo('manage waybills');
            //     })
            //     ->canSee(function ($request) {
            //         return $request instanceof ActionRequest
            //             || ($this->resource->exists && $this->resource->waybill_status == 'loading'
            //                 && $request->user()->hasPermissionTo('manage waybills'));
            //     }),

            // (new Actions\PrintWaybill)->onlyOnDetail()
            //     ->confirmText('ต้องการพิมพ์ใบกำกับสินค้ารายการนี้?')
            //     ->confirmButtonText('พิมพ์')
            //     ->cancelButtonText("ไม่พิมพ์")
            //     ->canRun(function ($request, $model) {
            //         return true;
            //     }),


        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('waybill_type', '=', 'charter');
    }

    public static function relatableEmployees(NovaRequest $request, $query)
    {
        return $query->whereIn('type', ['พนักงานขับรถบริษัท', 'พนักงานขับรถร่วม']);
    }

    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }
    public function addOrder_charter(User $user, Order_charter $order_charter)
    {

        return false;
    }
}
