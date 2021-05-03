<?php

namespace App\Nova\Actions;

use App\Models\Order_loader;
use App\Models\Order_status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Wasandev\QrCodeScan\QrCodeScan;

class AddorderWaybillQrcode extends Action
{
    use InteractsWithQueue, Queueable;
    public function uriKey()
    {
        return 'add-order-waybill-qrcode';
    }
    public function name()
    {
        return 'จัดขึ้นสินค้าด้วย QR CODE';
    }
    public function __construct($model = null)
    {
        $this->model = $model;
    }
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $order_loader = Order_loader::where('tracking_no', '=', $fields->order_header)->get();
            if ($order_loader->to_branch->id <> $model->routeto_branch->dest_branch->id) {

                return Action::message('ใบรับส่งนี้ไม่สามารถนำเข้าใบกำกับนี้ได้ สาขาปลายทางไม่ถูกต้อง');
            }
            $order_loader->waybill_id = $model->id;
            $order_loader->save();

            // Order_status::Create([
            //     'order_header_id' => $order_loader->id,
            //     'status' => 'loaded',
            //     'user_id' => auth()->user()->id,
            // ]);
        }
        return Action::message('เพิ่มใบรับส่งเข้าใบกำกับเรียบร้อยแล้ว');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            QrCodeScan::make('Qr code ใบรับส่ง', 'order_header')   // Name -> label name, name_id -> save to column
                ->canInput()                        // the user able to input the code using keyboard, default false
                ->canSubmit()                       // on modal scan need to click submit to send the code to the input value, default false
                ->displayValue()                    // set qr size on detail, default 100
                ->qrSizeIndex()                     // set qr size on index, default 30
                ->qrSizeDetail()                    // set qr size on detail, default 100
                ->qrSizeForm()                      // set qr size on form, default 50
                ->viewable()                        // set viewable if has belongto value, default true
                ->displayWidth('520px')          // set display width, default auto
                ->rules('required')
        ];
    }
}
