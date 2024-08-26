<?php

namespace App\Nova;

use App\Nova\Filters\RouteToBranch;
use App\Nova\Filters\ShowByWaybillStatus;
use App\Nova\Filters\ToBranch;
use App\Nova\Filters\WaybillFromDate;
use App\Nova\Filters\WaybillToDate;
//use Epartment\NovaDependencyContainer\HasDependencies;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;

use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Wasandev\Waybillstatus\Waybillstatus;

class Branchrec_waybill extends Resource
{
    // use HasDependencies;
    public static $group = '8.สำหรับสาขา';
    public static $priority = 1;
    public static $polling = true;
    public static $pollingInterval = 90;
    public static $showPollingToggle = true;
    public static $globallySearchable = false;

    public static $perPageOptions = [50, 100, 150];
    public static $with = ['car', 'branch', 'user'];
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Branchrec_waybill::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->waybill_no . '-' . $this->car->car_regist;
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
        'id', 'waybill_no'
    ];
    public static $searchRelations = [
        'car' => ['car_regist'],
    ];
    public static function label()
    {
        return 'รายการรถเข้าสาขา';
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
            ID::make(),
            Status::make(__('Status'), 'waybill_status')
                ->loadingWhen(['in transit'])
                ->failedWhen(['cancel'])
                ->exceptOnForms(),
            BelongsTo::make('สาขาต้นทาง', 'branch', 'App\Nova\Branch')
                ->onlyOnIndex(),
            Text::make(__('Waybill no'), 'waybill_no')->readonly(),
            Date::make(__('Waybill date'), 'waybill_date')
                ->readonly()
                ->default(today())
                ->format('DD/MM/YYYY')
                ->exceptOnForms(),
            Select::make(__('Waybill type'), 'waybill_type')->options([
                'general' => 'ทั่วไป',
                'charter' => 'เหมาคัน',
                'express' => 'Express',
            ])->displayUsingLabels()
                ->default('general')
                ->exceptOnForms(),


            BelongsTo::make(__('Route to branch'), 'routeto_branch', 'App\Nova\Routeto_branch')
                ->nullable()
                //->showCreateRelationButton()
                ->onlyOnDetail(),
            BelongsTo::make(__('Car regist'), 'car', 'App\Nova\Car')
                ->searchable()
                ->sortable()
                ->exceptOnForms(),
            Text::make('ยอดค่าขนส่งที่กำหนด', 'fulltruckrate', function () {
                if ($this->waybill_type == 'general' || $this->waybill_type == 'express') {
                    $hasitem = count($this->routeto_branch->routeto_branch_costs);
                    if ($hasitem) {
                        $routeto_branch_cost = \App\Models\Routeto_branch_cost::where('routeto_branch_id', '=', $this->routeto_branch_id)
                            ->where('cartype_id', '=', $this->car->cartype_id)
                            ->first();
                        if (isset($routeto_branch_cost)) {
                            $fulltruckrate = $routeto_branch_cost->fulltruckrate;
                            return  number_format($fulltruckrate, 2, '.', ',');
                        }
                    }
                    return 0;
                }
                return 0;
            })->onlyOnDetail(),
            Number::make('ค่าขนส่ง', 'total_amount', function () {
                return number_format($this->branchrec_orders->sum('order_amount'), 2, '.', ',');
            })->exceptOnForms(),
            Currency::make('ค่าบรรทุก', 'waybill_payable')
                ->onlyOnDetail(),
            Number::make('ยอดเก็บปลายทาง', 'branchpay', function () {
                return number_format(
                    $this->branchrec_orders->where('paymenttype', '=', 'E')->sum('order_amount'),
                    2,
                    '.',
                    ','
                );
            })->exceptOnForms(),
            Currency::make('รายได้บริษัท', 'waybill_income')
                ->onlyOnDetail(),

            BelongsTo::make(__('Driver'), 'driver', 'App\Nova\Employee')
                ->searchable()
                ->sortable()
                ->onlyOnDetail(),
            BelongsTo::make(__('Loader'), 'loader', 'App\Nova\User')
                ->searchable()
                ->sortable()
                ->onlyOnDetail(),
            DateTime::make(__('วันเวลาออกจากสาขาต้นทาง'), 'departure_at')
                ->format('DD/MM/YYYY HH:mm')
                ->exceptOnForms()
                ->hideFromIndex()
                ->readonly(),
            DateTime::make(__('กำหนดถึงสาขาปลายทาง'), 'arrival_at')
                ->format('DD/MM/YYYY HH:mm')
                ->hideFromIndex()
                ->readonly(),
            DateTime::make(__('วันเวลาถึงสาขาปลายทางจริง'), 'arrivaled_at')
                ->format('DD/MM/YYYY HH:mm')
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
            Currency::make('ต้นทุนค่าจัดลงสินค้าของสาขา', 'branch_car_income'),
              
            HasMany::make(__('Order header'), 'branchrec_orders', 'App\Nova\Branchrec_order'),
            // ->canSee(function ($request) {
            //     return $this->waybill_status === 'arrival';
            // }),
            HasMany::make(__('Waybill status'), 'waybill_statuses', 'App\Nova\Waybill_status'),
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
        return [
            (new Waybillstatus())->width('full')
        ];
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
            (new ShowByWaybillStatus()),
            (new ToBranch()),
            (new WaybillFromDate()),
            (new WaybillToDate())
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
        return [

            (new Actions\WaybillArrival($request->resourceId))
                ->onlyOnDetail()
                ->confirmText('ต้องการกำหนดให้รถถึงสาขาปลายทาง')
                ->confirmButtonText('ใช่')
                ->cancelButtonText("ไม่ใช่")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('manage branchrec_waybills');
                })
                ->canSee(function ($request) {
                    return $request instanceof ActionRequest
                        || ($this->resource->exists && $this->resource->waybill_status == 'in transit' && $request->user()->hasPermissionTo('manage branchrec_waybills'));
                }),
            (new Actions\PrintWaybill)->onlyOnDetail()
                ->confirmText('ต้องการพิมพ์ใบกำกับสินค้ารายการนี้?')
                ->confirmButtonText('พิมพ์')
                ->cancelButtonText("ไม่พิมพ์")
                ->canRun(function ($request, $model) {
                    return true;
                }),

        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->role != 'admin') {
            $routeto_branch = \App\Models\Routeto_branch::where('dest_branch_id', $request->user()->branch_id)->get('id');
            return $query->whereIn('routeto_branch_id', $routeto_branch);
        } else {
            return $query;
        }
    }
}
