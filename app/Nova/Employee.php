<?php

namespace App\Nova;

use App\Nova\Metrics\EmployeeByBranch;
use App\Nova\Metrics\EmployeeByDept;
use App\Nova\Metrics\EmployeeByType;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Wasandev\InputThaiAddress\InputSubDistrict;
use Wasandev\InputThaiAddress\InputDistrict;
use Wasandev\InputThaiAddress\InputProvince;
use Wasandev\InputThaiAddress\InputPostalCode;
use Jfeid\NovaGoogleMaps\NovaGoogleMaps;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\DateTime;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;

class Employee extends Resource
{
    //public static $displayInNavigation = false;
    public static $group = '2.งานด้านบุคคล';
    public static $priority = 4;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Employee';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

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
        return 'ข้อมูลพนักงาน';
    }
    public static function singularLabel()
    {
        return 'พนักงาน';
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
            Image::make(__('Image'), 'imagefile')
                ->hideFromIndex(),
            Boolean::make(__('Status'), 'active'),
            new Panel('ข้อมูลพนักงาน', $this->empdetailFields()),
            new Panel('ข้อมูลการติดต่อ', $this->contactFields()),
            new Panel('ที่อยู่', $this->addressFields()),
            new Panel('สำหรับพนักงานขับรถ', $this->drivingFields()),

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
            HasOne::make(__('Assign user'), 'assign_user', 'App\Nova\User'),


        ];
    }
    protected function empdetailFields()
    {
        return [
            Text::make(__('Employee code'), 'employee_code')
                ->hideFromIndex()
                ->nullable(),
            BelongsTo::make(__('Branch'), 'branch', 'App\Nova\Branch')
                ->rules('required')
                ->sortable()
                ->showCreateRelationButton(),

            BelongsTo::make(__('Department'), 'department', 'App\Nova\Department')
                ->sortable()
                ->rules('required')
                ->showCreateRelationButton(),
            Select::make(__('Employee type'), 'type')->options([
                'ผู้บริหาร' => 'ผู้บริหาร',
                'พนักงานบริษัท' => 'พนักงานบริษัท',
                'พนักงานบริษัทร่วม' => 'พนักงานบริษัทร่วม',
                'แรงงาน' => 'แรงงาน',
                'พนักงานขับรถบริษัท' => 'พนักงานขับรถบริษัท',
                'พนักงานขับรถร่วม' => 'พนักงานขับรถร่วม',
            ])->displayUsingLabels()
                ->sortable(),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required'),
            Text::make(__('ID card number'), 'taxid')
                ->sortable()
                ->hideFromIndex()
                ->rules('required', 'digits:13', 'numeric'),

            Text::make(__('Nickname'), 'nickname')
                ->nullable()
                ->hideFromIndex(),

            BelongsTo::make(__('Position'), 'position', 'App\Nova\Position')
                ->hideFromIndex()
                ->showCreateRelationButton(),



            Select::make(__('Employee status'), 'status')->options([
                'ประจำ' => 'ประจำ',
                'ทดลองงาน' => 'ทดลองงาน',
                'สัญญาจ้าง' => 'สัญญาจ้าง',
                'ชั่วคราว' => 'ชั่วคราว',
                'รายวัน' => 'รายวัน',
                'ปลด/ไล่ออก' => 'ปลด/ไล่ออก',
                'ลาออก' => 'ลาออก',
                'เลิกจ้าง' => 'เลิกจ้าง',
                'นักศึกษาฝึกงาน' => 'นักศึกษาฝึกงาน'
            ])->displayUsingLabels()

                ->hideFromIndex(),

        ];
    }

    protected function contactFields()
    {
        return [
            Text::make(__('Email'), 'email')
                ->hideFromIndex(),
            Text::make(__('Phone'), 'phoneno')
                ->rules('required'),
            Text::make(__('Email'), 'email')
                ->hideFromIndex(),
            Text::make(__('Line'), 'line')->hideFromIndex(),
            Text::make(__('Facebook'), 'facebook')->hideFromIndex(),

        ];
    }


    protected function drivingFields()
    {
        return [
            Image::make(__('Driver license picture'), 'cardimage')
                ->hideFromIndex(),
            BelongsTo::make(__('Driving license type'), 'driving_license_type', 'App\Nova\Driving_license_type')
                ->hideFromIndex()
                ->nullable()
                ->showCreateRelationButton(),
            Text::make(__('Driver license number'), 'driving_license_no')->hideFromIndex(),
            Date::make(__('Driving license date'), 'driving_license_date')->hideFromIndex(),
            Date::make(__('driving license enddate'), 'driving_license_enddate')
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
                ->rules('required')
                ->hideFromIndex(),
            InputDistrict::make(__('District'), 'district')
                ->withValues(['district', 'amphoe', 'province', 'zipcode'])
                ->fromValue('amphoe')
                ->sortable()
                ->rules('required')
                ->hideFromIndex(),
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
            (new EmployeeByBranch()),
            (new EmployeeByDept()),
            (new EmployeeByType())

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
            new  Filters\Branch,
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
            (new Actions\SetEmployeeBranch),
            (new Actions\SetEmployeeDepartment),

            (new Actions\ImportEmployees)->canSee(function ($request) {
                return $request->user()->role == 'admin';
            }),
            (new DownloadExcel)->allFields()->withHeadings(),
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
