<?php

namespace App\Nova\Actions;

use App\Models\Branch;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\Select;

class SetOrderToBranch extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;
    public $onlyOnIndex = true;

    public function uriKey()
    {
        return 'set-order-to_branch';
    }
    public function name()
    {
        return 'เปลี่ยนสาขาปลายทาง';
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


            $model->branch_rec_id = $fields->branch_rec;
            $model->save();
        }
        return Action::message('เปลี่ยนสาขาแล้ว');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $branches = Branch::all()->pluck('name', 'id');

        return [

            Select::make('สาขาปลายทาง', 'branch_rec')
                ->options($branches)
                ->displayUsingLabels()
        ];
    }
}
