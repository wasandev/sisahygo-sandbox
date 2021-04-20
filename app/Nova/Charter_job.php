<?php

namespace App\Nova;

use App\Nova\Actions\CharterJobsActive;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Http\Requests\NovaRequest;

class Charter_job extends Resource
{
    public static $group = "6.งานขนส่งแบบเหมา";
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Charter_job';
    public static $priority = 5;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'job_no';


    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'job_no'
    ];

    public static function label()
    {
        return 'ใบงานขนส่งเหมาคัน';
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
            Status::make('Status')
                ->loadingWhen(['New'])
                ->failedWhen(['Cancel'])
                ->exceptOnForms(),
            BelongsTo::make('สาขาที่ทำรายการ', 'branch', 'App\Nova\Branch')
                ->onlyOnDetail(),
            DateTime::make('วันที่', 'job_date')
                ->format('DD/MM/YYYY')
                ->readonly(true),
            Text::make('เลขที่ใบงานขนส่ง', 'job_no')
                ->readonly(true),

            BelongsTo::make('ลูกค้า/ผู้ว่าจ้าง', 'customer', 'App\Nova\Customer')
                ->searchable()
                ->withSubtitles(),
            // Select::make('เงื่อนไขการชำระเงิน', 'paymenttype')->options([
            //     'H' => 'เงินสด',
            //     'T' => 'เงินโอน',
            //     'F' => 'วางบิล',
            // ])->displayUsingLabels()
            //     ->hideFromIndex(),


            BelongsTo::make('เลขที่ใบเสนอราคา', 'quotation', 'App\Nova\Quotation')
                ->nullable()
                ->searchable()
                ->withSubtitles()
                ->hideFromIndex(),
            Text::make('อ้างถึงใบสั่งซื้อลูกค้า', 'reference')
                ->hideFromIndex()
                ->nullable(),

            BelongsTo::make('เลือกราคาค่าขนส่งเหมาคัน', 'charter_price', 'App\Nova\Charter_price')
                ->hideFromIndex()
                ->rules('required'),
            Currency::make('จำนวนค่าขนส่ง(บาท)', 'sub_total')
                ->readonly(true)
                ->onlyOnDetail(),

            Currency::make('ส่วนลด(บาท)', 'discount')
                ->nullable()
                ->default(0.00)
                ->hideFromIndex(),

            Currency::make('รวมจำนวนเงิน(บาท)', 'total')
                ->rules('required')
                ->onlyOnDetail(),
            Text::make('หมายเหตุ/เงื่อนไขอื่นๆ', 'terms')
                ->hideFromIndex(),
            BelongsTo::make('พนักงานตรวจสอบรายการ', 'employee', 'App\Nova\Employee')
                ->hideFromIndex()
                ->rules('required')
                ->searchable(),
            DateTime::make('วันที่สร้างรายการ', 'created_at')

                ->onlyOnDetail(),
            DateTime::make('วันที่แก้ไขล่าสุด', 'updated_at')

                ->onlyOnDetail(),
            BelongsTo::make(__('Created by'), 'user', 'App\Nova\User')
                ->onlyOnDetail(),
            DateTime::make(__('Created At'), 'created_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make(__('Updated by'), 'user_update', 'App\Nova\User')
                ->OnlyOnDetail(),
            DateTime::make(__('Updated At'), 'updated_at')
                ->format('DD/MM/YYYY HH:mm')
                ->onlyOnDetail(),
            BelongsTo::make('ใบรับส่ง', 'order_charter', 'App\Nova\Order_charter')
                ->exceptOnForms(),

            HasMany::make('จุดรับส่ง-รายการสินค้า', 'charter_job_items', 'App\Nova\Charter_job_item'),
            BelongsToMany::make('ค่าบริการอื่นๆ', 'service_charges', 'App\Nova\Service_charge')
                ->fields(function () {
                    return [
                        Currency::make('จำนวนเงิน', 'amount')
                    ];
                }),
            // HasOne::make('ประกันภัยสินค้า', 'charter_job_insurance', 'App\Nova\Charter_job_insurance')
            //     ->nullable(),


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
        if ($request->resourceId !== null) {
            $job = $request->findModelOrFail($request->resourceId);
            $hasitem = count($job->charter_job_items);
            if ($job->status == 'Cancel') {
                return [];
            }
            if ($job->status == 'Confirmed') {
                return [
                    (new Actions\CharterJobCancel)
                        ->onlyOnDetail()
                        ->confirmText('ต้องการยกเลิกใบรับงานเหมาคันรายการนี้?')
                        ->confirmButtonText('ยกเลิก')
                        ->cancelButtonText("ไม่ยกเลิก")
                        ->canRun(function ($request, $model) {
                            return true;
                        }),

                    (new Actions\PrintCharterJob)
                        ->onlyOnDetail()
                        ->confirmText('ต้องการพิมพ์ใบรับงานเหมาคัน?')
                        ->confirmButtonText('พิมพ์')
                        ->cancelButtonText("ยกเลิก")
                        ->canRun(function ($request, $model) {
                            return true;
                        }),
                ];
            }
            if ($job->status == 'New' && $hasitem == 0) {
                return [
                    (new Actions\CharterJobCancel)
                        ->onlyOnDetail()
                        ->confirmText('ต้องการยกเลิกใบรับงานเหมาคันรายการนี้?')
                        ->confirmButtonText('ยกเลิก')
                        ->cancelButtonText("ไม่ยกเลิก")
                        ->canRun(function ($request, $model) {
                            return true;
                        }),


                ];
            }
        }
        return [
            CharterJobsActive::make($request->resourceId)
                ->onlyOnDetail()
                ->confirmText('ต้องการยืนยันใบรับงานเหมาคันรายการนี้?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText("ไม่ยืนยัน")
                ->canRun(function ($request, $model) {
                    return true;
                }),

            (new Actions\CharterJobCancel)
                ->onlyOnDetail()
                ->confirmText('ต้องการยกเลิกใบรับงานเหมาคันรายการนี้?')
                ->confirmButtonText('ยกเลิก')
                ->cancelButtonText("ไม่ยกเลิก")
                ->canRun(function ($request, $model) {
                    return true;
                }),

            (new Actions\PrintCharterJob)
                ->onlyOnDetail()
                ->confirmText('ต้องการพิมพ์ใบรับงานเหมาคัน?')
                ->confirmButtonText('พิมพ์')
                ->cancelButtonText("ยกเลิก")
                ->canRun(function ($request, $model) {
                    return true;
                }),


        ];
    }
}
