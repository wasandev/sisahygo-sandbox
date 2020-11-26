<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Anaseqal\NovaImport\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\File;
use App\Imports\CarsImport;
use Maatwebsite\Excel\Facades\Excel;



class ImportCars extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    //public $onlyOnIndex = true;
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function name()
    {
        return __('Import Cars');
    }

    /**
     * @return string
     */
    public function uriKey(): string
    {
        return 'import-cars';
    }
    public function handle(ActionFields $fields, Collection $models)
    {
        Excel::import(new CarsImport, $fields->file);
        return Action::message('นำเข้าข้อมูลแล้ว');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            File::make('File')
                ->rules('required'),
        ];
    }
}
