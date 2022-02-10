<?php

namespace App\Nova\Actions;

use App\Models\Tableprice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class CopyProductPrice extends Action
{
    use InteractsWithQueue, Queueable;

    public function uriKey()
    {
        return 'copy-product-price';
    }
    public function name()
    {
        return 'คัดลอกตารางราคา';
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

            DB::table('tableprices as t')
                ->join('productservice_prices as p', 'p.tableprice_id', '=', 't.id')
                ->update(['t.data' => DB::raw('p.data')]);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $tableprice = Tableprice::where('status', true)->pluck('name', 'id');
        return [
            Select::make('คัดลอกจากตารางราคา', 'tableprice')
                ->options($tableprice)
                ->displayUsingLabels(),

        ];
    }
}
