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
        $date1 = '';
        $date2 = '';
        $date3 = '';
        $date4 = '';
        $date5 = '';
        $date6 = '';
        $date7 = '';
        $date8 = '';
        $date9 = '';
        $date13 = '';
        $date140 = '';
        $date141 = '';
        $pay10 = '';
        $pay11 = '';
        $pay12  = '';
        $pay13 = '';
        $pay14 = '';
        $pay15 = '';
        $pay16 = '';
        $pay17 = '';
        $pay18 = '';
        $pay1131 = '';
        $pay1130 = '';
        $tax10 = '';
        $tax11 = '';
        $tax12 = '';
        $tax13 = '';
        $tax14 = '';
        $tax15 = '';
        $tax16 = '';
        $tax17 = '';
        $tax18 = '';
        $tax1130 = '';
        $tax1131 = '';


        foreach ($models as $model) {

            $company = CompanyProfile::find(1);

            $vendordata = Vendor::find($model->vendor_id);

            $chk4 = 'Off';
            $chk7 = 'Off';
            if ($model->incometype->taxform == '3') {
                $chk4 = 'Yes';
            } elseif ($model->incometype->taxform == '53') {
                $chk7 = 'Yes';
            }
            switch ($model->incometype->code) {
                case '001':
                    $date1 = $model->pay_date;
                    $pay10 = $model->pay_amount;
                    $tax10 = $model->tax_amount;
                    break;
                case '002':
                    $date2 = $model->pay_date;
                    $pay11 = $model->pay_amount;
                    $tax11 = $model->tax_amount;
                    break;
                case '003':
                    $date3 = $model->pay_date;
                    $pay12 = $model->pay_amount;
                    $tax12 = $model->tax_amount;
                    break;
                case '004':
                    $date4 = $model->pay_date;
                    $pay13 = $model->pay_amount;
                    $tax13 = $model->tax_amount;
                    break;
                case '005':
                    $date5 = $model->pay_date;
                    $pay14 = $model->pay_amount;
                    $tax14 = $model->tax_amount;
                    break;
                case '006':
                    $date6 = $model->pay_date;
                    $pay15 = $model->pay_amount;
                    $tax15 = $model->tax_amount;
                    break;
                case '007':
                    $rate1 = $model->taxrate;
                    $date8 = $model->pay_date;
                    $pay17 = $model->pay_amount;
                    $tax17 = $model->tax_amount;
                    break;
                case '008':
                    $date7 = $model->pay_date;
                    $pay16 = $model->pay_amount;
                    $tax16 = $model->tax_amount;
                    break;
                case '009':
                    $rate1 = $model->taxrate;
                    $date8 = $model->pay_date;
                    $pay17 = $model->pay_amount;
                    $tax17 = $model->tax_amount;
                    break;
                case '010':
                    $rate1 = $model->taxrate;
                    $date8 = $model->pay_date;
                    $pay17 = $model->pay_amount;
                    $tax17 = $model->tax_amount;
                    break;
                case '011':
                    $date9 = $model->pay_date;
                    $pay18 = $model->pay_amount;
                    $tax18 = $model->tax_amount;
                    break;
                case '098':
                    $spac3 = $model->description;
                    $date141 = $model->pay_date;
                    $pay1131 = $model->pay_amount;
                    $tax1131 = $model->tax_amount;
                    break;
                default:
                    $date140 = formatDateThai($model->pay_date);
                    $pay1130 = $model->pay_amount;
                    $tax1130 = $model->tax_amount;
                    break;
            }

            $form_wh3path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . 'wh3_sisahygo.pdf';
            $form_name = 'wh3_' . $model->vendor_id . '.pdf';
            $xfdf_name = 'wh3_' . $model->vendor_id . '.xfdf';
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
                'date1' => $date1,
                'date2' => $date2,
                'date3' => $date3,
                'date4' => $date4,
                'date5' => $date5,
                'date6' => $date6,
                'date7' => $date7,
                'date8' => $date8,
                'date9' => $date9,
                'date10' => '',
                'date11' => '',
                'date12' => '',
                'date13' => '',
                'date140' => $date140,
                'date141' => $date141,
                'pay1130' => $pay1130,
                'pay1131' => $pay1131,
                'pay1.14' => number_format($model->pay_amount, 2, '.', ','),
                'tax1130' => $tax1130,
                'tax1131' => number_format($tax1131, 2, '.', ','),
                'tax1.14' =>  number_format($model->tax_amount, 2, '.', ','),
                'total' => baht_text($model->tax_amount),
                'chk8' => 'Yes',
                'date_pay' => date('d', strtotime($model->pay_date)),
                'month_pay' => '   ' . date('m', strtotime($model->pay_date)),
                'year_pay' => date("Y", strtotime($model->pay_date)) + 543
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
