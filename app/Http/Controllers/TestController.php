<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Routeto_branch;

use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        $routeto_branch = Routeto_branch::find(7);
        $from_branch = Branch::find($routeto_branch->branch_id);
        $to_branch = Branch::find($routeto_branch->dest_branch_id);
        $routeto_branchname = $from_branch->name . '-' . $to_branch->name;
        $from_latlng = $from_branch->location_lat . ',' . $from_branch->location_lng;
        $to_latlng = $to_branch->location_lat . ',' . $to_branch->location_lng;

        $distdata  = get_distance($from_latlng, $to_latlng);
        // dd($distdata);
        echo $routeto_branchname . ' = ' . $distdata['distance'] . 'กม. เวลาเดินทาง' . $distdata['duration'];
    }
}
