<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Branch_area;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        $customer_rec = Customer::find(2);
        echo $customer_rec->district;
        //$brancharea = DB::table('branch_areas')->where('district', $customer_rec->district)->first();
        $brancharea = Branch_area::firstWhere('district', $customer_rec->district);

        dd($brancharea->branch_id);
    }
}
