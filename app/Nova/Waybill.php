<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class Waybill extends Resource
{
    use HasDependencies;
    public static $group = '7.งานบริการขนส่ง';
    public static $priority = 3;
    public static $polling = true;
    public static $pollingInterval = 90;
    public static $showPollingToggle = true;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Waybill::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    //public static $title = 'waybill_no';

    public function title()
    {
        return $this->waybill_no . ' ' . $this->car->car_regist;
    }
    public static $sub_title = 'car_regist';


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
        return 'รายการใบกำกับสินค้า';
    }
    public static function singularLabel()
    {
        return 'ใบกำกับสินค้า';
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
            //ID::make(__('ID'), 'id')->sortable(),
            Status::make(__('Status'), 'waybill_status')
                ->loadingWhen(['loading'])
                ->failedWhen(['cancel'])
                ->exceptOnForms(),
            Text::make(__('Waybill no'), 'waybill_no')->readonly(),
            Date::make(__('Waybill date'), 'waybill_date')
                ->readonly()
                ->default(today())
                ->format('DD/MM/YYYY'),
            Select::make(__('Waybill type'), 'waybill_type')->options([
                'general' => 'ทั่วไป',
                'charter' => 'เหมาคัน',
                'express' => 'Express',
            ])->displayUsingLabels()
                ->default('general'),
            Text::make('สาขาปลายทาง', 'branch_rec', function () {
                if ($this->waybill_type <> 'charter') {
                    return $this->routeto_branch->name;
                }
                return $this->charter_route->name;
            })->onlyOnIndex(),
            NovaDependencyContainer::make([
                BelongsTo::make(__('Route to branch'), 'routeto_branch', 'App\Nova\Routeto_branch')
                    ->nullable()
                    ->showCreateRelationButton(),
            ])->dependsOnNot('waybill_type', 'charter'),
            NovaDependencyContainer::make([
                BelongsTo::make(__('Charter route'), 'charter_route', 'App\Nova\Charter_route')
                    ->nullable()
                    ->showCreateRelationButton(),
            ])->dependsOn('waybill_type', 'charter'),
            BelongsTo::make(__('Car regist'), 'car', 'App\Nova\Car')
                ->searchable()
                ->sortable(),
            Text::make('ยอดค่าขนส่งที่กำหนด', 'fulltruckrate', function () {
                if ($this->waybill_type == 'general') {
                    $hasitem = count($this->routeto_branch->routeto_branch_costs);
                    if ($hasitem) {
                        $routeto_branch_cost = \App\Models\Routeto_branch_cost::where('routeto_branch_id', '=', $this->routeto_branch_id)
                            ->where('cartype_id', '=', $this->car->cartype_id)
                            ->first();

                        $fulltruckrate = $routeto_branch_cost->fulltruckrate;
                        return  number_format(
                            $fulltruckrate,
                            2,
                            '.',
                            ','
                        );
                    }
                }
                return 0;
            })->exceptOnForms(),
            Number::make('ค่าขนส่ง', 'total_amount', function () {
                return number_format($this->order_loaders->sum('order_amount'), 2, '.', ',');
            })->exceptOnForms(),



            BelongsTo::make(__('Driver'), 'driver', 'App\Nova\Employee')
                ->searchable()
                ->sortable()
                ->hideFromIndex(),
            BelongsTo::make(__('Loader'), 'loader', 'App\Nova\Employee')
                ->searchable()
                ->sortable()
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
            HasMany::make(__('Order header'), 'order_loaders', 'App\Nova\Order_loader'),
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
        return '/resources/' . static::uriKey();
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }
    public function addOrder_loader(User $user, Order_loader $order_loader)
    {

        return false;
    }
}
