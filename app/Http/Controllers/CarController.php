<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Car_balance;
use App\Models\Carpayment;
use App\Models\Carreceive;
use App\Models\CompanyProfile;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use mikehaertl\pdftk\Pdf;


class CarController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth']);
    }

    public function carpaymentprint($carpayment)
    {
        $doc_title = "ใบสำคัญจ่าย";
        $company = CompanyProfile::find(1);
        $carpayment = Carpayment::find($carpayment);


        return view('documents.printcarpayment', compact('carpayment', 'company', 'doc_title'));
    }
    public function carreceiveprint($carreceive)
    {
        $doc_title = "ใบสำคัญรับ";
        $company = CompanyProfile::find(1);
        $carreceive = Carreceive::find($carreceive);


        return view('documents.printcarreceive', compact('carreceive', 'company', 'doc_title'));
    }
    public function report_11($from, $to, $type)
    {
        $report_title = 'รายงานสรุปจ่ายเงินรถ';
        $company = CompanyProfile::find(1);
        if ($type == 'all') {
            $carpayments = Carpayment::where('payment_date', '>=', $from)
                ->where('payment_date', '<=', $to)
                ->where('status', true)
                ->orderBy('payment_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        } else {
            $carpayments = Carpayment::where('payment_date', '>=', $from)
                ->where('payment_date', '<=', $to)
                ->where('type', '=', $type)
                ->where('status', true)
                ->orderBy('payment_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }
        $payment_groups = $carpayments->groupBy(function ($item) {
            return $item->payment_date->format('Y-m-d');
        });
        $payment_date = $payment_groups->all();
        return view('reports.carpaymentreportbyday', compact('company', 'report_title', 'carpayments', 'payment_date', 'from', 'to', 'type'));
    }
    public function report_12($from, $to)
    {
        $report_title = 'รายงานสรุปรับเงินรถ';
        $company = CompanyProfile::find(1);
        $carreceives = Carreceive::where('receive_date', '>=', $from)
            ->where('receive_date', '<=', $to)
            ->where('status', true)
            ->orderBy('receive_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $receive_groups = $carreceives->groupBy(function ($item) {
            return $item->receive_date->format('Y-m-d');
        });
        $receive_date = $receive_groups->all();
        return view('reports.carreceivereportbyday', compact('company', 'report_title', 'carreceives', 'receive_date', 'from', 'to'));
    }

    public function report_13($car, $from, $to)
    {
        $report_title = 'รายงานบัญชีคุมรถ';
        $company = CompanyProfile::find(1);
        $cardata = Car::find($car);
        $recforword = Car_balance::where('car_id', $car)
            ->where('cardoc_date', '<', $from)
            ->where('doctype', '=', 'R')
            ->sum('amount');
        $payforword = Car_balance::where('car_id', $car)
            ->where('cardoc_date', '<', $from)
            ->where('doctype', '=', 'P')
            ->sum('amount');

        $carcards = Car_balance::where('car_id', $car)
            ->where('cardoc_date', '>=', $from)
            ->where('cardoc_date', '<=', $to)
            ->orderBy('cardoc_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('reports.carcardreportbyday', compact('company', 'report_title', 'carcards', 'recforword', 'payforword', 'cardata', 'from', 'to'));
    }
    public function report_14($owner, $from, $to)
    {
        $report_title = 'รายงานบัญชีคุมรถตามเจ้าของรถ';
        $company = CompanyProfile::find(1);
        $ownerdata = Vendor::find($owner);
        $recforword = Car_balance::where('vendor_id', $owner)
            ->where('cardoc_date', '<', $from)
            ->where('doctype', '=', 'R')
            ->sum('amount');
        $payforword = Car_balance::where('vendor_id', $owner)
            ->where('cardoc_date', '<', $from)
            ->where('doctype', '=', 'P')
            ->sum('amount');

        $ownercards = Car_balance::where('vendor_id', $owner)
            ->where('cardoc_date', '>=', $from)
            ->where('cardoc_date', '<=', $to)
            ->orderBy('cardoc_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $car_groups = $ownercards->groupBy(function ($item) {
            return $item->car_id;
        });
        $car_groups = $car_groups->all();

        return view('reports.ownercardreportbyday', compact('company', 'report_title', 'ownercards', 'car_groups', 'recforword', 'payforword', 'ownerdata', 'from', 'to'));
    }
    public function report_15($to)
    {
        $report_title = 'รายงานสรุปยอดคงเหลือของรถ';
        $company = CompanyProfile::find(1);


        $car_balances = Car_balance::where('cardoc_date', '<=', $to)
            ->orderBy('vendor_id', 'asc')
            ->orderBy('car_id', 'asc')
            ->get();

        $balance_groups = $car_balances->groupBy(['vendor_id', 'car_id']);

        $balance_groups = $balance_groups->all();

        return view('reports.carsummaryreportbyday', compact('company', 'report_title', 'car_balances', 'balance_groups',  'to'));
    }

    // public function printwhtaxform($vendor, $from, $to)
    // {
    //     $report_title = 'หนังสือรับรองการหักภาษี ณ ที่จ่าย';
    //     $company = CompanyProfile::find(1);

    //     $vendordata = Vendor::find($vendor);
    //     $car_payment = Carpayment::where('vendor_id', $vendor)
    //         ->where('payment_date', '>=', $from)
    //         ->where('payment_date', '<=', $to)
    //         ->orderBy('vendor_id', 'asc')
    //         ->get();


    //     $form_wh3path =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . 'wh3_form.pdf';
    //     $form_name = 'wh3_' . $vendor . $from . '.pdf';
    //     $form_wh3saved =  Storage::disk('public')->getAdapter()->getPathPrefix() . 'documents/' . $form_name;

    //     $form_wh3 = new Pdf($form_wh3path);

    //     $result = $form_wh3->allow('AllFeatures')
    //         ->fillForm([

    //             'name1' => $company->company_name,
    //             'id1' => substr($company->taxid, 0, 1) . ' ' . substr($company->taxid, 1, 4) . ' ' . substr($company->taxid, 5, 5) . ' ' . substr($company->taxid, 10, 2) . ' ' . substr($company->taxid, 12, 1),
    //             'add1' => $company->address . ' ' . $company->sub_district . ' ' . $company->district . ' ' . $company->province . ' ' . $company->postal_code,
    //             'name2' => $vendordata->name,
    //             'id2' => $vendordata->taxid,
    //             'add2' => $vendordata->address . ' ' . $vendordata->sub_district . ' ' . $vendordata->district . ' ' . $vendordata->province . ' ' . $vendordata->postal_code,
    //         ])
    //         ->needAppearances()
    //         ->saveAs($form_wh3saved);

    //     // Always check for errors
    //     if ($result === false) {
    //         $error = $form_wh3->getError();
    //         echo $error;
    //     }
    //     return view('reports.wh3form', compact('form_name'));
    // }
}
