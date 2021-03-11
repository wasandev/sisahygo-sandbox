<?php

namespace App\Nova;

use App\Nova\Metrics\CustomerByPaymentType;
use App\Nova\Metrics\CustomerByPtype;
use App\Nova\Metrics\CustomerByType;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Number;
use Wasandev\InputThaiAddress\InputSubDistrict;
use Wasandev\InputThaiAddress\InputDistrict;
use Wasandev\InputThaiAddress\InputProvince;
use Wasandev\InputThaiAddress\InputPostalCode;
use Jfeid\NovaGoogleMaps\NovaGoogleMaps;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use App\Nova\Metrics\CustomersByProvince;
use App\Nova\Metrics\CustomersByDistrict;
use App\Nova\Metrics\CustomersPerDay;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Http\Requests\NovaRequest;

class Customer extends Resource
{
    //public static $displayInNavigation = false;
    public static $group = "4.งานด้านการตลาด";
    public static $priority = 2;
    public static $preventFormAbandonment = true;
    //public static $with = ['addresses'];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Customer';

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

        return   $this->address . ' ' . $this->sub_district . ' ' . $this->district . ' ' . $this->province;
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
        return __('Customers');
    }
    public static function singularLabel()
    {
        return __('Customer');
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
            //ID::make()->sortable(),
            Boolean::make(__('Status'), 'status')->hideFromIndex(),
            Text::make(__('Customer code'), 'customer_code')
                ->readonly()
                ->hideFromIndex(),

            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required', 'max:250'),

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
                ->withMeta(['value' => 'H'])
                ->displayUsingLabels(),
            Number::make(__('Credit term'), 'creditterm')
                ->withMeta(['value' => 0])
                ->hideFromIndex(),
            BelongsTo::make(__('Business type'), 'businesstype', 'App\Nova\Businesstype')
                ->showCreateRelationButton()
                ->sortable(),

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
            //HasMany::make(__('Customer addresses'), 'addresses', 'App\Nova\Address'),
            //BelongsToMany::make(__('Customer products'), 'product', 'App\Nova\Product'),
            //HasMany::make(__('Customer shipping cost'), 'customer_product_prices', 'App\Nova\Customer_product_price'),
            HasOne::make(__('Assign user'), 'assign_customer', 'App\Nova\User')
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            HasMany::make('รายการส่งสินค้า', 'order_sends', 'App\Nova\Order_header'),
            HasMany::make('รายการรับสินค้า', 'order_recs', 'App\Nova\Order_header'),

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
                ->sortable()
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

            Text::make(__('Address'), 'address')
                ->rules('required')
                ->sortable()
                ->hideFromIndex(),
            InputSubDistrict::make(__('Sub District'), 'sub_district')
                ->withValues(['district', 'amphoe', 'province', 'zipcode'])
                ->fromValue('district')
                ->sortable()
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

    //     //dd()
    //     $from_branch = $request->user()->branch_id;
    //     $to_branch =  $request->user()->branch_rec_id;

    //     if (!is_null($from_branch)) {
    //         if ($request->route()->parameter('field') === "customer") {
    //             $branch_area = \App\Models\Branch_area::where('branch_id', $from_branch)->get('district');
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
        return [
            (new CustomersByProvince())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('edit customers');
            }),
            (new CustomersByDistrict())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('edit customers');
            }),
            (new CustomerByType())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('edit customers');
            }),
            (new CustomerByPtype())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('edit customers');
            }),
            (new CustomerByPaymentType())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('edit customers');
            }),
            (new CustomersPerDay())->canSee(function ($request) {
                return  $request->user()->hasPermissionTo('edit customers');
            }),
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
            new Filters\BusinessType,
            new Filters\CustomerType,
            new Filters\CustomerPaymentType,
            new Filters\District,
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
            new Lenses\MostValueableSenders(),
            new Lenses\MostValueableReceivers()
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
                    return $request->user()->hasPermissionTo('edit customers');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit customers');
                }),
            (new Actions\SetCustomerPtype)
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit customers');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit customers');
                }),
            (new Actions\SetCustomerPaymentType)
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit customers');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit customers');
                }),
            (new DownloadExcel)->allFields()->withHeadings()
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),
            (new Actions\ImportCustomers)
                ->canSee(function ($request) {
                    return $request->user()->role == 'admin';
                }),


        ];
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
