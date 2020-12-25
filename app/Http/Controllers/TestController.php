<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branchrec_order;
use Illuminate\Http\Request;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        $branch_balances = Branchrec_order::where('waybill_id', 9)
            ->where('paymenttype', '=', 'E')->get();

        $cust_groups = $branch_balances->groupBy('customer_rec_id')->all();

        $bal_custs = $cust_groups;

        // dd($bal_custs);
        foreach ($bal_custs as $cust => $cust_groups) {
            echo $cust;
            echo $cust_groups->sum('order_amount');
            echo "<ul>";
            foreach ($cust_groups as $cust_group) {
                echo "<li>" . $cust_group->order_header_no . "</li>";
            }
            echo "</ul>";
        }
    }
}
