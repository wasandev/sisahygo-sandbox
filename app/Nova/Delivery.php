<?php

namespace App\Nova;

use App\Nova\Filters\Branch;
use App\Nova\Filters\DeliveryDateFilter;
use App\Nova\Filters\DeliveryToDateFilter;
use App\Nova\Filters\BranchType;
use App\Nova\Filters\Deliverytype;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class Delivery extends Resource
{
    public static $group = '8.สำหรับสาขา';
    public static $priority = 3;
    // public static $polling = true;
    // public static $pollingInterval = 90;
    // public static $showPollingToggle = true;
    //public static $globallySearchable = true;
    
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Delivery::class;    
    public static $with = ['branch'];

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
    public static $searchRelations = [
        'car' => ['car_regist']
    ];
    public static function label()
    {
        return 'รายการจัดส่งสินค้า';
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
            ID::make(__('ID'), 'id')
                ->sortable(),
            Boolean::make(__('Status'), 'completed')
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('manage branchrec_orders');
                }),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch')
                ->exceptOnForms()
                ->sortable()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('manage branchrec_orders');
                }),
            Date::make('วันที่จัดส่ง', 'delivery_date')
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('manage branchrec_orders');
                })
                ->sortable(),
            Text::make('เลขที่จัดส่ง', function () {
                return $this->delivery_no;
            })

                ->onlyOnDetail(),

            Select::make('ประเภทการจัดส่ง', 'delivery_type')->options([
                '0' => 'รถบรรทุกจัดส่ง',
                '1' => 'สาขาจัดส่ง'
            ])->displayUsingLabels()
                ->exceptOnForms()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('manage branchrec_orders');
                }),


            BelongsTo::make(__('Car regist'), 'car', 'App\Nova\Car')

                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('manage branchrec_orders');
                })
                ->sortable(),
            BelongsTo::make(__('Driver'), 'driver', 'App\Nova\Employee')
                ->hideFromIndex()
                ->searchable(),
            Text::make(__('Description'), 'description')
                ->hideFromIndex()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('manage branchrec_orders');
                }),
            Currency::make('จำนวนเงินที่ต้องจัดเก็บ', 'receipt_amount')
                ->readonly(),
            BelongsTo::make('เส้นทาง', 'branch_route', 'App\Nova\Branch_route'),
            BelongsTo::make('พนักงานจัดส่ง', 'sender', 'App\Nova\User')
                ->rules('required')
                ->searchable()
                ->hideFromIndex(),

            Currency::make('ต้นทุนการจัดส่ง',function() {
                return $this->delivery_costitems->sum('amount');                
            })->exceptOnForms(),
            

            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('manage branchrec_orders');
                }),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            Number::make('เลขไมล์เริ่มต้น','mile_start_number')
                ->nullable()
                ->hideFromIndex(),
            Number::make('เลขไมล์สิ้นสุด','mile_end_number')
                ->nullable()
                ->hideFromIndex(),           
            Number::make('รวมระยะทางขนส่ง', 'delivery_mile')
                 ->nullable()
                ->hideFromIndex(),        
            HasMany::make('รายการจัดส่งตามผู้รับ', 'delivery_items', 'App\Nova\Delivery_item'),
            HasMany::make('ต้นทุนการจัดส่ง', 'delivery_costitems', 'App\Nova\Delivery_costitem'),

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
            new Branch(),
            new DeliveryDateFilter(),
            new DeliveryToDateFilter(),
            new Deliverytype(),
            new BranchType()

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
            (new Actions\PrintDelivery)->onlyOnDetail()
                ->confirmText('ต้องการพิมพ์ใบจัดส่งสินค้ารายการนี้?')
                ->confirmButtonText('พิมพ์')
                ->cancelButtonText("ไม่พิมพ์")
                ->canRun(function ($request) {
                    return  $request->user()->hasPermissionTo('edit deliveries');
                })
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('edit deliveries');
                }),
            (new Actions\SetDeliveryMile)->onlyOndetail()
                ->canRun(function ($request) {
                    return  $request->user()->hasPermissionTo('edit deliveries');
                })
                ->canSee(function ($request) {
                    return  $request->user()->hasPermissionTo('edit deliveries');
                }),
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->role == 'driver') {
            return   $query->where('branch_id', $request->user()->branch_id)
                ->where('sender_id', $request->user()->id);
        } elseif ($request->user()->branch->code <> '001') {
            return  $query->where('branch_id', $request->user()->branch_id);
        } else {
            return $query;
        }
    }
}
