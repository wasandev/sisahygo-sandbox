<?php

namespace App\Nova;

use App\Nova\Filters\LoaderShowByOrderStatus;
use App\Nova\Filters\LoaderToBranch;
use App\Nova\Filters\LoaderToDistrict;
use App\Nova\Filters\OrderToBranch;
use App\Nova\Filters\ShowByOrderStatus;
use App\Nova\Filters\ToDistrict;
use App\Nova\Lenses\ValueByBranch;
use App\Nova\Lenses\ValueByDistrict;
use Illuminate\Database\Eloquent\Builder;
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
use Laravel\Nova\Http\Requests\ActionRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Wasandev\Orderstatus\Orderstatus;

class Order_loader extends Resource
{

    public static $group = '7.งานบริการขนส่ง';
    public static $priority = 3;
    public static $globallySearchable = false;
    public static $perPageOptions = [50, 100, 150];
    public static $with = ['customer', 'to_customer', 'user', 'branch', 'to_branch'];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_loader::class;


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
        'order_header_no', 'id'
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

        return 'รายการจัดขึ้นสินค้า';
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
            Status::make(__('Order status'), 'order_status')
                ->loadingWhen(['confirmed'])
                ->failedWhen(['cancel'])
                ->exceptOnForms(),
            BelongsTo::make(__('To branch'), 'to_branch', 'App\Nova\Branch')
                ->exceptOnForms(),
            Select::make('ประเภท', 'order_type')->options([
                'general' => 'ทั่วไป',
                'express' => 'Express',
            ])->onlyOnIndex()
                ->displayUsingLabels(),

            Text::make('อำเภอ', 'branch_district', function () {
                return $this->to_customer->district;
            })->onlyOnIndex(),

            Text::make(__('Order header no'), 'order_header_no')
                ->readonly()
                ->sortable(),

            BelongsTo::make('ใบกำกับสินค้า', 'waybill', 'App\Nova\Waybill')
                ->nullable(),



            BelongsTo::make('ผู้ส่งสินค้า', 'customer', 'App\Nova\Customer')
                ->sortable()
                ->exceptOnForms(),
            BelongsTo::make('ผู้รับสินค้า', 'to_customer', 'App\Nova\Customer')
                ->sortable()
                ->exceptOnForms(),

            Currency::make('จำนวนเงิน', 'order_amount')
                ->exceptOnForms(),
            Select::make(__('Tran type'), 'trantype')->options([
                '0' => 'รับเอง',
                '1' => 'จัดส่ง',
            ])->displayUsingLabels()
                ->sortable(),
            BelongsTo::make(__('Loader'), 'loader', 'App\Nova\User')
                ->nullable()
                ->searchable()
                ->hideFromIndex(),

            Text::make(__('Remark'), 'remark')->nullable()
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
        return [
            (new Orderstatus()),
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
            new LoaderToBranch(),
            new LoaderToDistrict(),
            new LoaderShowByOrderStatus(),
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
            new ValueByBranch(),
            new ValueByDistrict(),

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
            (new Actions\OrderLoaded())
                //->onlyOnDetail()
                ->confirmText('ต้องการจัดสินค้าขึ้นรถใบรับส่งรายการนี้?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('manage waybills');
                }),

            (new DownloadExcel)->allFields()->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                })
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        $branch = \App\Models\Branch::find($request->user()->branch_id);
        $resourceTable = 'order_headers';
        $query->select("{$resourceTable}.*");
        $query->addSelect('c.district as customerDistrict');
        $query->join('customers as c', "{$resourceTable}.customer_rec_id", '=', 'c.id');
        if ($branch->code == '001') {
            $query->whereNotIn('order_headers.order_status', ['checking', 'new', 'cancel'])
                ->where('order_headers.shipto_center', '=', '2')
                ->where('order_headers.order_type', '<>', 'charter');
        } else {
            $query->whereNotIn('order_status', ['checking', 'new', 'cancel'])
                ->where('order_headers.branch_id', '=', $request->user()->branch_id)
                ->where('order_headers.order_type', '<>', 'charter');
        }
        $query->when(empty($request->get('orderBy')), function (Builder $q) use ($resourceTable) {
            $q->getQuery()->orders = null;

            return $q->orderBy('customerDistrict', 'asc')
                ->orderBy('order_headers.id', 'asc')
                ->orderBy('order_headers.waybill_id', 'asc');
        });
    }

    public static function relatableWaybills(NovaRequest $request, $query)
    {
        if (isset($request->resourceId)) {
            $resourceId = $request->resourceId;
            $order_loader = \App\Models\Order_loader::find($resourceId);

            $routeto_branch = \App\Models\Routeto_branch::where('dest_branch_id',  $order_loader->branch_rec_id)->first();
            if (isset($routeto_branch)) {
                return $query->where('routeto_branch_id', '=', $routeto_branch->id)
                    ->where('waybill_status', '=', 'loading');
            }
        }
    }

    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }

    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/' . static::uriKey();
    }
}
