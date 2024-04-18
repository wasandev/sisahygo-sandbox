<?php

namespace App\Nova;

use App\Nova\Metrics\Branchtrends\BranchBalanceDeliveryFrom;
use App\Nova\Metrics\Branchtrends\BranchBalanceFrom;
use App\Nova\Metrics\Branchtrends\BranchBalanceNotpayFrom;
use App\Nova\Metrics\Branchtrends\BranchBalancePayFrom;
use App\Nova\Metrics\Branchtrends\BranchBalanceWarehouseFrom;
use App\Nova\Metrics\Branchtrends\BranchOrderFrom;
use App\Nova\Metrics\Branchtrends\BranchrecWaybillFrom;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Panel;
use Wasandev\InputThaiAddress\InputSubDistrict;
use Wasandev\InputThaiAddress\InputDistrict;
use Wasandev\InputThaiAddress\InputProvince;
use Wasandev\InputThaiAddress\InputPostalCode;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Jfeid\NovaGoogleMaps\NovaGoogleMaps;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Number;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Laravel\Nova\Fields\Boolean;

class Branch extends Resource
{
    use HasDependencies;

    //public static $displayInNavigation = false;
    public static $group = '1.งานสำหรับผู้ดูแลระบบ';
    public static $priority = 2;
    //public static $showColumnBorders = true;

    //public static $subGroup = "ข้อมูลบริษัท";
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Branch';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    public static function availableForNavigation(Request $request)
    {
        return $request->user()->hasPermissionTo('edit branches');
    }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
    ];

    public static function label()
    {
        return __('Branches');
    }
    public static function singularLabel()
    {
        return __('Branch');
    }
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {

        // $nestedBranchAreasForm = NestedForm::make('Branch_areas');
        // $nestedBranchAreasForm = $nestedBranchAreasForm->showOnDetail(true);

        return [

            ID::make(),
            Text::make(__('Branch Code'), 'code')
                ->rules('required')
                ->sortable(),
            Text::make(__('Name'), 'name')->sortable()
                ->rules('required'),

            Text::make(__('Phone'), 'phoneno')
                ->rules('required'),

            new Panel(__('Address'), $this->addressFields()),
            Select::make(__('Branch Type'), 'type')->options([
                'owner' => 'บริษัทเป็นเจ้าของ',
                'partner' => 'ร่วมบริการ'
            ])->displayUsingLabels()
                ->rules('required')
                ->sortable(),
            Number::make('% บริษัท', 'partner_rate')
                ->onlyOnIndex()
                ->sortable(),
            Number::make('% Dropship', 'dropship_rate')
                ->onlyOnIndex()
                ->sortable(),
            NovaDependencyContainer::make([
                BelongsTo::make(__('Vendor'), 'vendor', 'App\Nova\Vendor')
                    ->searchable()
                    ->showCreateRelationButton()
                    ->nullable(),
                Number::make('รายได้บริษัท(%)', 'partner_rate'),
                Boolean::make('Dropship', 'dropship_flag'),
                Number::make('รายได้ Dropship (%)', 'dropship_rate')->nullable()
                    ->help('รายได้ของผู้ร่วมบริการต้นทาง(Dropship)%'),
            ])->dependsOn('type', 'partner'),
            HasMany::make(__('Branch Areas'), 'branch_areas', 'App\Nova\Branch_area'),
            // BelongsToMany::make(__('Route to branch'), 'routeto', 'App\Nova\Branch')
            //     ->fields(function () {
            //         return [
            //             Text::make('ชื่อเส้นทาง', 'name')->exceptOnForms(),
            //             Number::make('ระยะทาง(กม.)', 'distance')->step('0.01'),
            //             Number::make('เวลาเก็บสินค้าที่สาขาต้นทาง(วัน)', 'collectdays')->step('0.01'),

            //         ];
            //     }),
            //HasMany::make(__('Branch Routes'), 'branch_routes', 'App\Nova\Branch_route'),

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
            HasMany::make('เส้นทางขนส่งของสาขา', 'branch_routes', 'App\Nova\Branch_route'),
            HasMany::make('รายการเก็บเงินปลายทาง', 'branch_balances', 'App\Nova\Branch_balance')
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
                ->rules('required')
                ->hideFromIndex(),
            InputDistrict::make(__('District'), 'district')
                ->withValues(['district', 'amphoe', 'province', 'zipcode'])
                ->fromValue('amphoe')
                ->sortable()
                ->rules('required'),
            InputProvince::make(__('Province'), 'province')
                ->withValues(['district', 'amphoe', 'province', 'zipcode'])
                ->fromValue('province')
                ->sortable()
                ->rules('required')
                ->hideFromIndex(),
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
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [

            (new BranchOrderFrom())->width('full')->onlyOnDetail(),
            (new BranchrecWaybillFrom())->width('1/2')->onlyOnDetail(),
            (new BranchBalanceFrom())->width('1/2')->onlyOnDetail(),
            (new BranchBalancePayFrom())->width('1/2')->onlyOnDetail(),
            (new BranchBalanceNotpayFrom())->width('1/2')->onlyOnDetail(),
            (new BranchBalanceWarehouseFrom())->width('1/2')->help('รวมทุกประเภทการชำระเงิน')->onlyOnDetail(),
            (new BranchBalanceDeliveryFrom())->width('1/2')->help('รวมทุกประเภทการชำระเงิน')->onlyOnDetail(),
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
        return [
            new Lenses\MostValueBranchDiscount(),
            //new Lenses\MostValueBranchDistrict()

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
            (new Actions\AddBranchArea)
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit branches');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit branches');
                }),
            // (new Actions\ImportBranches)->canSee(function ($request) {
            //     return $request->user()->role == 'admin';
            // }),
            (new DownloadExcel)->allFields()->withHeadings()
                ->canRun(function ($request) {
                    return $request->user()->hasPermissionTo('edit branches');
                })
                ->canSee(function ($request) {
                    return $request->user()->hasPermissionTo('edit branches');
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
