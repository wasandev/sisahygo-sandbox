<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;

use Illuminate\Support\Arr;

class PrintManyOrder extends Action
{
    use InteractsWithQueue, Queueable;
    

    
    public function uriKey()
    {
        return 'print-many-order';
    }
    public function name()
    {
        return __('Print Orders');
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
        $tobranch_value = $fields->to_branch;       
        $from_value = $fields->from;
        $to_value = $fields->to;

        return Action::openInNewTab('/orderheader/printorders/' . $tobranch_value . '/' . $from_value . '/' . $to_value);
    }
    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        
        $branchs = \App\Models\Branch::pluck('name', 'id');
        return [
            Select::make('เลือกสาขาปลายทาง', 'to_branch')
                ->options($branchs)
                ->rules('required')
                ->help('เลือกสาขาปลายทางที่ต้องการ'),
            Date::make('วันที่เริ่มต้น', 'from')
                ->rules('required'),
            Date::make('วันที่สิ้นสุด', 'to')
                ->rules('required')
        
        ];
    }
}
