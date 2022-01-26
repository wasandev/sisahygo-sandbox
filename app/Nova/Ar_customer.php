<?php

namespace App\Nova;


use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Number;
use Wasandev\InputThaiAddress\InputSubDistrict;
use Wasandev\InputThaiAddress\InputDistrict;
use Wasandev\InputThaiAddress\InputProvince;
use Wasandev\InputThaiAddress\InputPostalCode;
use Jfeid\NovaGoogleMaps\NovaGoogleMaps;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Laravel\Nova\Http\Requests\NovaRequest;

class Ar_customer extends Resource
{
    public static $group = '9.1 งานลูกหนี้การค้า';
    public static $priority = 1;
    public static $preventFormAbandonment = true;


    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Ar_customer';

    public static function availableForNavigation(Request $request)
    {
        return $request->user()->hasPermissionTo('edit ar_customer');
    }
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    //public static $title = 'name';
    public function title()
    {
        return $this->name;
    }

    public function subtitle()
    {
        return $this->sub_district . ' ' . $this->district . ' ' . $this->province;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'phoneno'
        //'sub_district',  'district', 'province'
    ];

    public static function label()
    {
        return 'ข้อมูลลูกค้าวางบิล';
    }
    public static function singularLabel()
    {
        return 'ลูกค้าวางบิล';
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
            Boolean::make(__('Status'), 'status'),
            Text::make(__('Customer code'), 'customer_code')
                ->readonly()
                ->hideFromIndex(),

            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:250', 'unique:customers,name'),

            Text::make(__('Tax ID'), 'taxid')
                ->hideFromIndex(),
            //->rules('digits:13', 'numeric'),
            Select::make(__('Type'), 'type')->options([
                'company' => 'นิติบุคคล',
                'person' => 'บุคคลธรรมดา'
            ])
                ->displayUsingLabels()
                ->hideFromIndex(),

            Select::make(__('Payment type'), 'paymenttype')->options([
                'H' => 'เงินสดต้นทาง',
                'E' => 'เงินสดปลายทาง',
                'Y' => 'วางบิล'
            ])
                ->hideFromIndex()
                ->withMeta(['value' => 'Y'])
                ->displayUsingLabels(),
            Number::make(__('Credit term'), 'creditterm')
                ->hideFromIndex(),
            BelongsTo::make(__('Business type'), 'businesstype', 'App\Nova\Businesstype')
                ->hideFromIndex()
                ->showCreateRelationButton(),

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

            new Panel('ข้อมูลการติดต่อ', $this->contactFields()),
            new Panel('ที่อยู่ในการออกเอกสาร', $this->addressFields()),
            new Panel('อื่นๆ', $this->otherFields()),
            // HasMany::make(__('Customer addresses'), 'addresses', 'App\Nova\Address'),
            //BelongsToMany::make(__('Customer products'), 'product', 'App\Nova\Product'),
            //HasMany::make(__('Customer shipping cost'), 'customer_product_prices', 'App\Nova\Customer_product_price'),
            // HasOne::make(__('Assign user'), 'assign_customer', 'App\Nova\User')
            //     ->canSee(function ($request) {
            //         return $request->user()->role == 'admin';
            //     }),
            //HasMany::make('รายการส่งสินค้า', 'order_sends', 'App\Nova\Order_header'),
            //HasMany::make('รายการรับสินค้า', 'order_recs', 'App\Nova\Order_header'),
            HasMany::make('รายการวางบิล', 'ar_balances', 'App\Nova\Ar_balance'),
            HasMany::make('ใบแจ้งหนี้', 'invoices', 'App\Nova\Invoice'),


        ];
    }

    /**
     * Get the address fields for the resource.
     *
     * @return array
     */
    protected function contactFields()
    {
        return [
            Text::make(__('Contact name'), 'contactname')
                ->hideFromIndex(),
            Text::make(__('Email'), 'email')
                ->hideFromIndex(),
            Text::make(__('Phone'), 'phoneno')
                ->rules('required')
                ->hideFromIndex(),
            Text::make(__('Website Url'), 'weburl')
                ->hideFromIndex(),
            Text::make(__('Facebook'), 'facebook')
                ->hideFromIndex(),
            Text::make(__('Line'), 'line')
                ->hideFromIndex(),

        ];
    }
    /**
     * Get the address fields for the resource.
     *
     * @return array
     */
    protected function addressFields()
    {
        return [

            Text::make(__('Address'), 'address')->hideFromIndex()
                ->rules('required'),
            InputSubDistrict::make(__('Sub District'), 'sub_district')
                ->withValues(['district', 'amphoe', 'province', 'zipcode'])
                ->fromValue('district')
                ->rules('required'),
            InputDistrict::make(__('District'), 'district')
                ->withValues(['district', 'amphoe', 'province', 'zipcode'])
                ->fromValue('amphoe')
                ->sortable()
                ->rules('required'),
            InputProvince::make(__('Province'), 'province')
                ->withValues(['district', 'amphoe', 'province', 'zipcode'])
                ->fromValue('province')
                ->sortable()
                ->rules('required'),
            InputPostalCode::make(__('Postal Code'), 'postal_code')
                ->withValues(['district', 'amphoe', 'province', 'zipcode'])
                ->fromValue('zipcode')
                ->rules('required')
                ->hideFromIndex(),

            NovaGoogleMaps::make(__('Google Map Address'), 'location')->setValue($this->location_lat, $this->location_lng)
                ->hideFromIndex(),

        ];
    }
    /**
     * Get the address fields for the resource.
     *
     * @return array
     */
    protected function otherFields()
    {
        return [
            Image::make(__('Logo'), 'logofile')
                ->hideFromIndex(),
            Image::make(__('Image'), 'imagefile')
                ->hideFromIndex(),
            Textarea::make(__('Other'), 'description')
                ->hideFromIndex(),

        ];
    }

    // public static function relatableQuery(NovaRequest $request, $query)
    // {

    //     // if ($request->resourceId && $request->resource == 'order_headers' && $request->editMode === 'update') {

    //     //     $order = \App\Models\Order_header::find($request->resourceId);
    //     //     $from_branch = $order->branch_id;
    //     //     $to_branch =  $order->branch_rec_id;

    //     //     if ($request->route()->parameter('field') === "customer") {
    //     //         $branch_area = \App\Models\Branch_area::where('branch_id', $from_branch)->get('district');
    //     //         return $query->whereIn('district', $branch_area);
    //     //     }
    //     //     if ($request->route()->parameter('field') === "to_customer") {
    //     //         $to_branch_area = \App\Models\Branch_area::where('branch_id', $to_branch)->get('district');
    //     //         return $query->whereIn('district', $to_branch_area);
    //     //     }
    //     // }
    //     $from_branch = $request->user()->branch_id;
    //     $to_branch =  $request->user()->branch_rec_id;

    //     if (!is_null($from_branch)) {
    //         if ($request->route()->parameter('field') === "customer") {
    //             $branch_area = \App\Models\Branch_area::where('branch_id', $from_branch)->get();
    //             return $query->whereIn('district', $branch_area);
    //         }
    //     }
    //     if (!is_null($to_branch)) {
    //         if ($request->route()->parameter('field') === "to_customer") {
    //             $to_branch_area = \App\Models\Branch_area::where('branch_id', $to_branch)->get('district');
    //             return $query->whereIn('district', $to_branch_area);
    //         }
    //     } else {
    //         if ($request->route()->parameter('field') === "to_customer") {
    //             $to_branch_area = \App\Models\Branch_area::where('branch_id', '<>', $from_branch)->get('district');
    //             return $query->whereIn('district', $to_branch_area);
    //         }
    //     }
    // }
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
            new Filters\BusinessType,
            new Filters\Province,
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
            // new Lenses\MostValueableSenders(),
            // new Lenses\MostValueableReceivers()
            //new ArcardReport()
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
            (new Actions\SetCustomerType)
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit ar_customer');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit ar_customer');
                }),
            (new Actions\SetCustomerPtype)
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit ar_customer');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit ar_customer');
                }),
            (new Actions\SetCustomerPaymentType)
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit ar_customer');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit ar_customer');
                }),
            (new DownloadExcel)->allFields()->withHeadings()
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit ar_customer');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit ar_customer');
                }),
            // (new Actions\ImportCustomers)->canSee(function ($request) {
            //     return $request->user()->role == 'admin';
            // }),


        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {

        return $query->where('paymenttype', '=', 'Y');
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
