<?php

namespace App\Http\Controllers;

use App\Models\Carpayment;
use App\Models\Carreceive;
use App\Models\CompanyProfile;
use Illuminate\Http\Request;

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
}
