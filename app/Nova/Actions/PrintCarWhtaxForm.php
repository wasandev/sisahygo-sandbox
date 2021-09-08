<?php

namespace App\Nova\Actions;

use App\Models\Carpayment;
use App\Models\CompanyProfile;
use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use mikehaertl\pdftk\Pdf;
use mikehaertl\pdftk\XfdfFile;

class PrintCarWhtaxForm extends Action
{
    use InteractsWithQueue, Queueable;
    public $withoutActionEvents = true;

    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }
    public function uriKey()
    {
        return 'print-car-whtax-form';
    }
    public function name()
    {
        return 'พิมพ์หนังสือรับรองฯ';
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
            $decodedFilters = collect(json_decode(base64_decode($this->filter), true));
            $from  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\CarpaymentFromDate');
            $from_value = Arr::get($from, 'value');


            if ($from_value == '') {
                return Action::danger('เลือก วันที่เริ่มต้น ที่ต้องการที่เมนูกรองข้อมูลก่อน');
            }
            $to  =  $decodedFilters->firstWhere('class', 'App\Nova\Filters\CarpaymentToDate');
            $to_value = Arr::get($to, 'value');
            if ($to_value == '') {
                return Action::danger('เลือก วันที่สิ้นสุด ที่ต้องการที่เมนูกรองข้อมูลก่อน');
            }
            $company = CompanyProfile::find(1);

            $vendordata = Vendor::find($model->id);
            $chk4 = 'Off';
            $chk7 = 'Off';
            if ($vendordata->type == 'company') {
                $chk7 = 'Yes';
            } else {
                $chk4 = 'Yes';
            }
            $car_payment = Carpayment::where('vendor_id', $model->id)
                ->where('payment_date', '>=', $from_value)
                ->where('payment_date', '<=', $to_value)
                ->orderBy('vendor_id', 'asc')
                ->get();


            $form_wh3path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . 'wh3_form.pdf';
            $form_name = 'wh3_' . $model->id . $from_value . '.pdf';
            $xfdf_name = 'wh3_' . $model->id . $from_value . '.xfdf';
            $form_wh3saved =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $form_name;
            $xfdf_file =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $xfdf_name;

            $form_wh3 = new Pdf($form_wh3path);
            $car_taxfill = [
                'name1' => $company->company_name,
                'id1' => substr($company->taxid, 0, 1) . ' ' . substr($company->taxid, 1, 4) . ' ' . substr($company->taxid, 5, 5) . ' ' . substr($company->taxid, 10, 2) . ' ' . substr($company->taxid, 12, 1),
                'add1' => $company->address . ' ' . $company->sub_district . ' ' . $company->district . ' ' . $company->province . ' ' . $company->postal_code,
                'name2' => $vendordata->name,
                'id1_2' => substr($vendordata->taxid, 0, 1) . ' ' . substr($vendordata->taxid, 1, 4) . ' ' . substr($vendordata->taxid, 5, 5) . ' ' . substr($vendordata->taxid, 10, 2) . ' ' . substr($vendordata->taxid, 12, 1),
                'add2' => $vendordata->address . ' ' . $vendordata->sub_district . ' ' . $vendordata->district . ' ' . $vendordata->province . ' ' . $vendordata->postal_code,
                'chk4' => $chk4,
                'chk7' => $chk7,
                'date14.0' => date("d/m/Y", strtotime($to_value)),
                'pay1.13.0' => '2500.00',
                'tax1.13.0' => '25.00',
                'pay1.14' => '2500.00',
                'tax1.14' => '25.00',
                'chk8' => 'Yes',
                'total' => 'สองพันห้าร้อยบาทถ้วน'
            ];

            $xfdf = new XfdfFile($car_taxfill);
            $xfdf->saveAs($xfdf_file);

            $result = $form_wh3->allow('AllFeatures')
                ->fillForm($xfdf_file)
                ->needAppearances()
                ->saveAs($form_wh3saved);

            // Always check for errors
            if ($result === false) {
                $error = $form_wh3->getError();
                echo $error;
            }

            return Action::openInNewTab(url('storage/documents/' . $form_name));
        }
    }
    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
