<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Panel;
use Illuminate\Http\Request;
use Wasandev\InputThaiAddress\InputSubDistrict;
use Wasandev\InputThaiAddress\InputDistrict;
use Wasandev\InputThaiAddress\InputProvince;
use Wasandev\InputThaiAddress\InputPostalCode;
use Jfeid\NovaGoogleMaps\NovaGoogleMaps;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\DateTime;

class Vendor extends Resource
{
    //public static $displayInNavigation = false;
    public static $group = "3.งานด้านรถบรรทุก";
    public static $priority = 5;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Vendor';

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
        'name', 'district', 'province'
    ];
    public static function label()
    {
        return __('Vendors');
    }
    public static function singularLabel()
    {
        return __('Vendor');
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
            Boolean::make(__('Status'), 'status'),
            Text::make(__('Owner code'), 'owner_code')
                ->sortable()
                ->onlyOnDetail(),
            Text::make(__('Name'), 'name')
                ->sortable()
                ->rules('required'),
            Text::make(__('Tax ID'), 'taxid')
                ->hideFromIndex(),
            Select::make(__('Type'), 'type')
                ->options([
                    'company' => 'นิติบุคคล',
                    'person' => 'บุคคลธรรมดา'
                ])
                ->displayUsingLabels()
                ->hideFromIndex(),
            Select::make(__('Payment type'), 'paymenttype')
                ->options([
                    'เงินสด' => 'เงินสด',
                    'วางบิล' => 'วางบิล'
                ])
                ->hideFromIndex()
                ->withMeta(['value' => 'เงินสด']),
            Number::make('ระยะเวลาเครดิต', 'creditterm')
                ->withMeta(['value' => 0])
                ->hideFromIndex(),
            BelongsTo::make('ประเภทธุรกิจ', 'businesstype', 'App\Nova\Businesstype')
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
            new Panel('ที่อยู่', $this->addressFields()),
            new Panel('ข้อมูลบัญชีธนาคารสำหรับโอนเงิน', $this->bankaccountFields()),
            new Panel('อื่นๆ', $this->otherFields()),


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
            Text::make(__('Contact name'), 'contractname')
                ->hideFromIndex(),
            Text::make(__('Phone'), 'phoneno'),
            Text::make(__('Web url'), 'weburl')
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
     * Get the address fields for the resource.
     *
     * @return array
     */
    protected function bankaccountFields()
    {
        return [
            Text::make(__('Bank Account no'), 'bankaccountno')
                ->hideFromIndex()
                ->nullable(),
            Text::make(__('Account name'), 'bankaccountname')
                ->hideFromIndex()
                ->nullable(),
            BelongsTo::make(__('Bank'), 'bank', 'App\Nova\Bank')
                ->hideFromIndex()
                ->nullable(),
            Text::make(__('Bank branch'), 'bankbranch')
                ->hideFromIndex()
                ->nullable(),
            Select::make(__('Account type'), 'account_type')
                ->options([
                    'saving' => 'ออมทรัพย์',
                    'current' => 'กระแสรายวัน',
                    'fixed' => 'ฝากประจำ'
                ])->displayUsingLabels()
                ->hideFromIndex()
                ->nullable()
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
            (new Actions\ImportVendors)->canSee(function ($request) {
                return $request->user()->role == 'admin';
            }),
        ];
    }
}
