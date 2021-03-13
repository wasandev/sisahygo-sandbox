<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\BelongsTo;
use Wasandev\InputThaiAddress\InputSubDistrict;
use Wasandev\InputThaiAddress\InputDistrict;
use Wasandev\InputThaiAddress\InputProvince;
use Wasandev\InputThaiAddress\InputPostalCode;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\DateTime;
use Jfeid\NovaGoogleMaps\NovaGoogleMaps;
use Laravel\Nova\Fields\Select;

class CompanyProfile extends Resource
{
    //public static $displayInNavigation = false;
    public static $group = '1.งานสำหรับผู้ดูแลระบบ';
    public static $priority = 1;
    //public static $showColumnBorders = true;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\CompanyProfile';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'company_name';

    public static function availableForNavigation(Request $request)
    {
        return $request->user()->hasPermissionTo('edit companyprofile');
    }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'company_name'
    ];

    public static function label()
    {
        return __("Company Profile");
    }
    public static function singularLabel()
    {
        return __('Company');
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
            Text::make(__('Company Name'), 'company_name')
                ->rules('required'),
            Text::make(__('Tax ID'), 'taxid')
                ->rules('required', 'digits:13', 'numeric'),
            new Panel(__('Address'), $this->addressFields()),
            new Panel(__('Contact Info'), $this->contactFields()),
            new Panel(__('Other'), $this->otherFields()),

            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
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
            Text::make(__('Phone'), 'phoneno')
                ->rules('required')
                ->hideFromIndex(),
            Text::make(__('Website Url'), 'weburl')
                ->hideFromIndex(),
            Text::make(__('Facebook'), 'facebook')
                ->hideFromIndex(),
            Text::make(__('Line'), 'line')
                ->hideFromIndex(),
            Text::make(__('Email'), 'email')
                ->hideFromIndex()
                ->rules('required'),

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
                ->hideFromIndex()
                ->rules("mimes:jpeg,bmp,png", "max:2048")
                ->help('ขนาดไฟล์ไม่เกิน 2 MB.'),

            Image::make(__('Image'), 'imagefile')
                ->hideFromIndex()
                ->rules("mimes:jpeg,bmp,png", "max:2048")
                ->help('ขนาดไฟล์ไม่เกิน 2 MB.'),
            Select::make('รูปแบบการพิมพ์ใบรับส่ง', 'orderprint_option')->options([
                'form1' => 'พิมพ์ลงฟอร์ม',
                'form2' => 'พิมพ์ลงกระดาษเปล่า(A5)',
                'form3' => 'พิมพ์กระดาษเทอร์มอล'
            ])->displayUsingLabels()
                ->hideFromIndex(),

            Textarea::make(__('Other'), 'description')->hideFromIndex(),

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
}
