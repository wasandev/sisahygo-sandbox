<?php

namespace App\Nova\Actions;

use App\Models\ApiClient;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class GenerateApiKey extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * ชื่อ Action ที่แสดงใน Nova
     *
     * @var string
     */
    public $name = 'สร้าง/เปลี่ยน API Key';

    /**
     * ระบุว่า Action นี้ใช้กับโมเดลทีละรายการ
     *
     * @var string
     */
    public $confirmButtonText = 'สร้าง API Key';
    public $cancelButtonText = 'ยกเลิก';

    /**
     * ทำงานเมื่อมีการเรียก Action
     *
     * @param  \Laravel\Nova\Fields\ActionFields       $fields
     * @param  \Illuminate\Support\Collection<int,\App\Models\ApiClient>  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        /** @var \App\Models\ApiClient $client */
        foreach ($models as $client) {
            // 1) สร้าง API Key แบบสุ่ม (Plaintext) ความยาว 64 ตัวอักษร (32 bytes → hex)
            $plainKey = bin2hex(random_bytes(32));

            // 2) แปลงเป็น hash (sha256) เพื่อเก็บในฐานข้อมูล
            $hashedKey = hash('sha256', $plainKey);

            $client->api_key = $hashedKey;
            $client->save();

            // 3) คืนค่าข้อความแจ้งเตือน (Nova จะแสดง popup ให้ copy key ได้)
            //    ข้อควรทราบ: plainKey นี้จะแสดงครั้งเดียวเท่านั้น
            return Action::message(
                    "<p><strong>สร้าง/เปลี่ยน API Key สำเร็จ</strong></p>
                    <p>กรุณาเก็บรักษา API KEY นี้ (แสดงครั้งเดียว):</p>

                    <div style='padding: 12px; background: #f5f5f5; border-radius: 6px; font-family: monospace; margin-bottom: 10px;'>
                        {$plainKey}
                    </div>

                    <button
                        style='padding: 8px 14px; background: #3b82f6; color: white; border-radius: 4px; cursor: pointer; border: none;'
                        onclick=\"navigator.clipboard.writeText('{$plainKey}'); alert('คัดลอก API Key แล้ว!');\">
                        คัดลอก API Key
                    </button>
                    "
);

        }

        return Action::danger('ไม่พบ ApiClient ที่เลือก');
    }

    /**
     * ถ้า Action นี้ต้องมีฟิลด์ให้กรอกเพิ่ม (เช่น rate limit ต่อครั้ง)
     * สามารถเพิ่มได้ในเมธอดนี้
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    public function fields()
    {
        return [
            // ตัวอย่างหากอนาคตอยากให้เลือกกำหนด rate limit ตอน generate
            // Number::make('Rate Limit / นาที', 'rate')
            //     ->min(1)->max(100000)->step(1)->default(60),
        ];
    }
}
