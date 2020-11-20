<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order_header extends Resource
{
    public static $group = '7.งานบริการขนส่ง';
    public static $priority = 1;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_header::class;


    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'order_header_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'order_header_no'
    ];

    public static $searchRelations = [
        'customer' => ['name'],
        'to_customer' => ['name']
    ];
    public static $globalSearchRelations = [
        'customer' => ['name'],
        'to_customer' => ['name']
    ];

    public static function label()
    {
        return 'ข้อมูลใบรับส่งสินค้า';
    }
    public static function singularLabel()
    {
        return 'ใบรับส่งสินค้า';
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
            Status::make(__('Order status'), 'order_status')
                ->loadingWhen(['new'])
                ->failedWhen(['cancel'])
                ->exceptOnForms(),
            //BelongsTo::make('ใบกำกับสินค้า','waybill_id','App\Nova\Waybill'),
            Text::make(__('Order header no'), 'order_header_no')
                ->readonly(),
            Date::make(__('Order date'), 'order_header_date')
                ->readonly()
                ->default(today())
                ->format('DD/MM/YYYY'),
            Select::make(__('Payment type'), 'paymenttype')->options([
                'H' => 'เงินสดต้นทาง',
                'T' => 'เงินโอนต้นทาง',
                'E' => 'เงินสดปลายทาง',
                'F' => 'วางบิลต้นทาง',
                'L' => 'วางบิลปลายทาง'
            ])->displayUsingLabels()
                ->hideWhenCreating()
                ->default('H'),
            Boolean::make(__('Payment status'), 'payment_status')
                ->exceptOnForms(),
            BelongsTo::make(__('From branch'), 'branch', 'App\Nova\Branch')
                ->hideWhenCreating()
                ->hideFromIndex(),

            BelongsTo::make(__('To branch'), 'to_branch', 'App\Nova\Branch')
                ->hideFromIndex(),
            // Text::make('ชื่อผู้ส่ง', function () {
            //     return $this->customer->name;
            // })
            //     ->onlyOnIndex(),

            // Text::make('ชื่อผู้รับ', function () {
            //     return $this->to_customer->name;
            // })
            //     ->onlyOnIndex(),
            BelongsTo::make('ผู้ส่งสินค้า', 'customer', 'App\Nova\Customer')
                ->searchable()
                ->showCreateRelationButton(),

            BelongsTo::make('ผู้รับสินค้า', 'to_customer', 'App\Nova\Customer')
                ->searchable()
                ->showCreateRelationButton(),
            Text::make(__('Remark'), 'remark')->nullable()
                ->hideFromIndex(),


            Currency::make('จำนวนเงิน', 'order_amount')
                ->exceptOnForms(),

            BelongsTo::make(__('Checker'), 'checker', 'App\Nova\User')
                ->onlyOnDetail(),
            BelongsTo::make(__('Loader'), 'loader', 'App\Nova\User')
                ->onlyOnDetail(),
            BelongsTo::make(__('Shipper'), 'shipper', 'App\Nova\User')
                ->onlyOnDetail(),
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
            HasMany::make(__('Order detail'), 'order_details', 'App\Nova\Order_detail'),
            HasMany::make(__('Order status'), 'order_statuses', 'App\Nova\Order_status'),
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
            (new Actions\OrderConfirmed($request->resourceId))
                ->onlyOnDetail()
                ->confirmText('ต้องการยืนยันใบรับส่งรายการนี้?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request, $model) {
                    return true;
                }),
        ];
    }

    public static function relatableCustomers(NovaRequest $request, $query)
    {
        $from_branch = $request->user()->branch_id;
        $to_branch =  2;
        if ($request->route()->parameter('field') == "customer") {
            $branch_area = \App\Models\Branch_area::where('branch_id', $from_branch)->get();
            return $query->whereIn('district', $branch_area);
        }
        if ($request->route()->parameter('field') == "to_customer") {
            $branch_area = \App\Models\Branch_area::where('branch_id', $to_branch)->get();
            return $query->whereIn('province', $branch_area);
        }
        //return $query;
    }
}
