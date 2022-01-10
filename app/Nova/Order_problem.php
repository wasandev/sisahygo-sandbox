<?php

namespace App\Nova;

use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Order_problem extends Resource
{
    use HasDependencies;
    public static $group = '7.งานบริการขนส่ง';
    public static $priority = 5;
    public static $polling = true;
    public static $pollingInterval = 90;
    public static $showPollingToggle = true;
    public static $globallySearchable = false;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order_problem::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'problem_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'problem_no',
    ];

    public static $searchRelations = [
        'customer' => ['name'],
    ];
    public static function label()
    {
        return 'รายการปัญหาการขนส่ง';
    }
    public static function singularLabel()
    {
        return 'ปัญหาการขนส่ง';
    }
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        if ($request->user()->branch_id == 1) {


            return [
                ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
                Status::make('สถานะ', 'status')
                    ->loadingWhen(['checking'])
                    ->failedWhen(['new'])
                    ->readonly()
                    ->sortable(),
                Text::make('เลขที่เอกสาร', 'problem_no')
                    ->readonly()
                    ->sortable(),
                Date::make('วันที่', 'problem_date')
                    ->readonly()
                    ->sortable(),

                BelongsTo::make('เลขที่ใบรับส่ง', 'order_header', 'App\Nova\Order_header')
                    ->sortable()
                    ->searchable()
                    ->showOnCreating(),




                new Panel('1.การรับเรื่อง', $this->problemFields()),
                new Panel('2.การตรวจสอบ', $this->checkFields()),
                new Panel('3.การพิจารณาและอนุมัติ', $this->discussFields()),
                new Panel('4.พนักงานที่เกี่ยวของ', $this->employeeFields()),
                new Panel('5.การชำระเงิน', $this->paymentFields()),

                HasMany::make('เอกสารประกอบ', 'order_problem_images', 'App\Nova\Order_problem_image')
            ];
        } else {
            return [
                ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
                Status::make('สถานะ', 'status')
                    ->loadingWhen(['checking'])
                    ->failedWhen(['new'])
                    ->readonly()
                    ->sortable(),
                Text::make('เลขที่เอกสาร', 'problem_no')
                    ->readonly()
                    ->sortable(),
                Date::make('วันที่', 'problem_date')
                    ->readonly()
                    ->sortable(),
                BelongsTo::make('เลขที่ใบรับส่ง', 'branchrec_order', 'App\Nova\Branchrec_order')
                    ->sortable()
                    ->searchable()
                    ->showOnCreating(),

                new Panel('1.การรับเรื่อง', $this->problemFields()),
                new Panel('2.การตรวจสอบ', $this->checkFields()),
                new Panel('3.การพิจารณาและอนุมัติ', $this->discussFields()),
                new Panel('4.พนักงานที่เกี่ยวของ', $this->employeeFields()),
                new Panel('5.การชำระเงิน', $this->paymentFields()),

                HasMany::make('เอกสารประกอบ', 'order_problem_images', 'App\Nova\Order_problem_image')
            ];
        }
    }

    /**
     * Get the address fields for the resource.
     *
     * @return array
     */
    protected function problemFields()
    {

        return [

            Text::make('จุดรับสินค้า', 'branchpoint', function () {
                return $this->order_header->branch->name . '-' . $this->order_header->to_branch->name;
            })->onlyOnDetail(),
            Boolean::make('การชำระค่าขนส่ง', 'payment_status', function () {
                return $this->order_header->payment_status;
            })->onlyOnDetail(),
            Text::make('ทะเบียนรถ', 'car', function () {
                if (isset($this->order_header->waybill)) {
                    return $this->order_header->waybill->car->car_regist;
                } else {
                    return null;
                }
            })->exceptOnForms(),
            Select::make('ลูกค้าที่เรียกร้อง', 'customer_flag')
                ->options([
                    'S' => 'ลูกค้าผู้ส่ง',
                    'R' => 'ลูกค้าผู้รับ',
                ])->showOnCreating()
                ->hideFromIndex(),
            BelongsTo::make('ลูกค้าผู้เรียกร้อง', 'customer', 'App\Nova\Customer')
                ->searchable()
                ->onlyOnDetail(),
            Text::make('ผู้แจ้งเรื่อง', 'contact_person')->nullable()
                ->hideFromIndex(),
            Text::make('โทรศัพท์', 'contact_phoneno')->nullable()
                ->hideFromIndex(),
            Select::make('ปัญหา', 'problem_type')->options([
                '1' => 'เสียหายทั้งหมด',
                '2' => 'เสียหายบางส่วน',
                '3' => 'สูญหายทั้งหมด',
                '4' => 'สูญหายทั้งหมด',
                '0' => 'อื่นๆ'
            ])->displayUsingLabels()
                ->rules('required'),
            Text::make('ระบุรายละเอียดปัญหา', 'problem_detail')
                ->nullable()
                ->hideFromIndex(),
            Text::make('ข้อเรียกร้อง', 'problem_claim')
                ->nullable()
                ->hideFromIndex(),
            Text::make('บุคคลที่เรียกร้อง', 'problem_personclaim')
                ->hideFromIndex(),
            Currency::make('จำนวนเงินที่เรียกร้อง', 'claim_amount')
                ->hideFromIndex()
        ];
    }

    protected function checkFields()
    {
        return [
            Select::make('ขั้นตอนการทำงานที่มีปัญหา', 'problem_process')->options([
                '1' => 'การตรวจรับ',
                '2' => 'การออกเอกสาร',
                '3' => 'คลังสินค้าต้นทาง',
                '4' => 'การขนส่ง',
                '5' => 'คลังสินค้าปลายทาง',
                '6' => 'การจัดส่ง'
            ])->displayUsingLabels()
                ->hideFromIndex()
                ->hideWhenCreating(),
            Text::make('รายละเอียดการตรวจสอบ', 'check_detail')
                ->nullable()
                ->hideFromIndex()
                ->hideWhenCreating(),

        ];
    }
    protected function discussFields()
    {
        return [

            Text::make('รายละเอียดการพิจารณา', 'discuss_detail')
                ->nullable()
                ->hideFromIndex()
                ->hideWhenCreating(),
            Currency::make('จำนวนเงินชดใช้ค่าเสียหาย', 'approve_amount')
                ->nullable()
                ->hideFromIndex()->hideWhenCreating(),
            Boolean::make('ตัดค่าขนส่งเป็นส่วนลด', 'order_amount_flag')
                ->nullable()
                ->hideFromIndex()
                ->help('กรณีวางบิลหรือเก็บเงินปลายทาง')
                ->hideWhenCreating()

        ];
    }
    protected function paymentFields()
    {
        return [
            Date::make('วันที่จ่าย', 'payment_date')
                ->hideWhenCreating()
                ->hideFromIndex(),

            Select::make('จ่ายด้วย', 'payment_by')->options([
                'H' => 'เงินสด',
                'T' => 'เงินโอน',
                'Q' => 'เช็ค'
            ])->displayUsingLabels()
                ->sortable()
                ->hideFromIndex()
                ->hideWhenCreating(),
            NovaDependencyContainer::make([
                Text::make('บัญชีเลขที่', 'bankaccount')
                    ->nullable(),
                BelongsTo::make(__('Bank'), 'bank', 'App\Nova\Bank')
                    ->nullable(),
                Text::make('ชื่อบัญชี', 'bankaccountname')
                    ->nullable()

            ])->dependsOn('payment_by', 'T'),

            NovaDependencyContainer::make([
                Text::make(__('Cheque No'), 'chequeno')
                    ->nullable(),
                Text::make(__('Cheque Date'), 'chequedate')
                    ->nullable(),
                BelongsTo::make(__('Cheque Bank'), 'chequebank', 'App\Nova\Bank')
                    ->nullable()
            ])->dependsOn('payment_by', 'Q'),
        ];
    }
    /**
     * Get the address fields for the resource.
     *
     * @return array
     */
    protected function employeeFields()
    {
        return [
            Text::make('พนักงานตรวจรับ', 'check_by', function () {
                return $this->order_header->checker->name;
            })->onlyOnDetail(),
            Text::make('พนักงานออกเอกสาร', 'billing_by', function () {
                return $this->order_header->user->name;
            })->onlyOnDetail(),
            Text::make('พนักงานจัดขึ้น', 'loader', function () {
                if (isset($this->order_header->loader)) {
                    return $this->order_header->loader->name;
                } else {
                    return  null;
                }
            })->onlyOnDetail(),
            Text::make('พนักงานจัดส่ง', 'shipper', function () {
                if (isset($this->order_header->shipper)) {
                    return $this->order_header->shipper->name;
                } else {
                    return null;
                }
            })->onlyOnDetail(),
            Text::make('พนักงานขับรถ', 'car_driver', function () {
                if (isset($this->order_header->waybill->car->driver)) {
                    return $this->order_header->waybill->car->driver->name;
                } else {
                    return null;
                }
            })->onlyOnDetail(),
            BelongsTo::make('พนักงานผู้แจ้งเรื่อง', 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            BelongsTo::make('ผู้รับเรื่อง', 'checker', 'App\Nova\User')
                ->onlyOnDetail(),

            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
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

    public static function relatableOrder_headers(NovaRequest $request, $query)
    {

        return $query->where('order_status', '<>', 'problem');
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
