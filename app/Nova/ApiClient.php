<?php

namespace App\Nova;

use App\Models\ApiClient as ApiClientModel;
use App\Nova\Actions\GenerateApiKey;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class ApiClient extends Resource
{
    /**
     * โมเดลที่ Resource นี้อ้างถึง
     *
     * @var class-string<\App\Models\ApiClient>
     */
    public static $model = ApiClientModel::class;


    /**
     * ฟิลด์ที่ใช้แสดงเป็น label ใน Nova
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * ฟิลด์ที่ใช้ค้นหา
     *
     * @var array<int, string>
     */
    public static $search = [
        'id',
        'name',
        'customer_id',
    ];

    /**
     * จัดกลุ่มเมนูใน Nova Sidebar
     *
     * @var string|null
     */
    public static $group = '1.งานสำหรับผู้ดูแลระบบ';
    public static $priority = 5;


    /**
     * กำหนดชื่อ Resource ที่แสดงในเมนู (ถ้าอยากใช้ชื่อภาษาไทย)
     */
    public static function label()
    {
        return 'API Clients';
    }

    public static function singularLabel()
    {
        return 'API Client';
    }

    /**
     * ฟิลด์ที่ใช้แสดงใน Nova
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('ชื่อแสดงผล', 'name')
                ->sortable()
                ->rules('required', 'max:255')
                ->help('ชื่อบริษัท / ลูกค้าองค์กร ที่ใช้ API'),

            Number::make('Customer ID', 'customer_id')
                ->sortable()
                ->rules('required', 'integer')
                ->help('อ้างอิง customer_id ในระบบ SISAHYGO'),

            Boolean::make('เปิดใช้งาน', 'is_active')
                ->trueValue(true)
                ->falseValue(false)
                ->sortable(),

            Number::make('Rate Limit / นาที', 'rate_limit_per_minute')
                ->min(1)
                ->max(100000)
                ->step(1)
                ->default(60)
                ->sortable()
                ->help('จำนวน request สูงสุด / นาที / API Key'),

            Textarea::make('Allowed IPs', 'allowed_ips')
                ->alwaysShow()
                ->help('ระบุรายการ IP ที่อนุญาต คั่นด้วยจุลภาค เช่น "1.1.1.1, 2.2.2.2" (ว่าง = ไม่จำกัด IP)'),

            Text::make('API Key (hash ในระบบ)', 'api_key')
                ->onlyOnDetail()
                ->readonly()
                ->help('เก็บเป็นค่า hash (sha256) ของ API Key เพื่อความปลอดภัย'),
        ];
    }

    /**
     * การกรองข้อมูล (ถ้ายังไม่ใช้ สามารถปล่อยว่างได้)
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * เลนส์ (ไม่จำเป็นต้องใช้)
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Action ที่สามารถใช้กับ Resource นี้
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(Request $request)
    {
        // ถ้าอยากจำกัดให้เฉพาะ admin ใช้ Action นี้ได้
        // สามารถเช็ค role/permission ที่นี่ได้เช่นกัน
        return [
            (new GenerateApiKey())
                ->onlyOnDetail()
                ->confirmText('ต้องการสร้าง/เปลี่ยน API Key ของลูกค้ารายนี้ใช่หรือไม่?')
                ->confirmButtonText('ยืนยัน')
                ->cancelButtonText('ยกเลิก'),
        ];
    }
}
