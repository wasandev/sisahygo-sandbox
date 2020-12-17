<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Waybill;
use App\Models\Order_loader;
use App\Models\Car;
use App\Models\Routeto_branch;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        // $routeto_branch = Routeto_branch::find(7);
        // $from_branch = Branch::find($routeto_branch->branch_id);
        // $to_branch = Branch::find($routeto_branch->dest_branch_id);
        // $routeto_branchname = $from_branch->name . '-' . $to_branch->name;
        // $from_latlng = $from_branch->location_lat . ',' . $from_branch->location_lng;
        // $to_latlng = $to_branch->location_lat . ',' . $to_branch->location_lng;

        // $distdata  = get_distance($from_latlng, $to_latlng);
        // // dd($distdata);
        // echo $routeto_branchname . ' = ' . $distdata['distance'] . 'กม. เวลาเดินทาง' . $distdata['duration'];
        // $waybills = Waybill::where('routeto_branch_id', '=', 7)
        //     ->pluck('waybill_no', 'id');
        // dd($waybills);
        // $array = [
        //     ['developer' => ['id' => 1, 'name' => 'Taylor']],
        // ];

        // $names = Arr::pluck($array, 'developer.id', 'developer.name');
        // dd($names);

        // $waybillOptions = array();
        // $order_loader =    Order_loader::find(16);
        // $routeto_branch = Routeto_branch::where('dest_branch_id',  $order_loader->branch_rec_id)->first();
        // // $waybills = Waybill::where('routeto_branch_id', '=', $routeto_branch->id)
        // //     ->pluck('waybill_no', 'id');
        // $waybills = Waybill::with('car')
        //     ->where('routeto_branch_id', '=', $routeto_branch->id)
        //     ->where('waybill_status', '=', 'loading')
        //     ->get();
        // //$waybills = Waybill::with('car')->where('routeto_branch_id', '=', 7)->get();
        // foreach ($waybills as $waybill) {
        //     $waybillOptions = [
        //         ['waybill' => ['id' => $waybill->id, 'name' => $waybill->waybill_no . '-' . $waybill->car->car_regist]],
        //     ];
        // }

        // $waybillOptions = collect($waybillOptions);
        // $waybillOptions = $waybillOptions->pluck('waybill.name', 'waybill.id');
        $routeto_branch = \App\Models\Routeto_branch::where('branch_id', 5)->get('id');
        //dd($routeto_branch);
        $waybills = \App\Models\Waybill::whereIn('routeto_branch_id', $routeto_branch)->get();

        foreach ($waybills as $waybill) {
            echo $waybill->waybill_no;
        }
        //return view('test.test');
    }
}
