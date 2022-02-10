<?php

namespace App\Nova\Actions;

use App\Models\Branch_area;
use App\Models\Branch;
use App\Models\Unit;
use App\Models\Customer;
use App\Models\Customer_product_price;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Select;

class CancelProblem extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;
    //public $onlyOnIndex = true;

    public function uriKey()
    {
        return 'cancel-problem';
    }
    public function name()
    {
        return 'ยกเลิกรายการแจ้งปัญหา';
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
            // $order = Order

        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {

        return [
            Select::make('สถานะใบรับส่ง', 'order_status')
                ->options([
                    'สินค้าอยู่คลังสำนักงานใหญ่' => 'confiremd',
                    'สินค้าอยู่คลังสาขาปลายทาง' => 'branch warehouse'
                ])->displayUsingLabels()
                ->rules('required')
        ];
    }
}
