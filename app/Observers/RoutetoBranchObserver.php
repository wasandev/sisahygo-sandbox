<?php

namespace App\Observers;

use App\Models\Routeto_branch;
use App\Models\Branch;

use function PHPUnit\Framework\isNan;
use function PHPUnit\Framework\isNull;

class RoutetoBranchObserver
{
    public function creating(Routeto_branch $routeto_branch)
    {
        $routeto_branch->user_id = auth()->user()->id;
        $from_branch = Branch::find($routeto_branch->branch_id);
        $to_branch = Branch::find($routeto_branch->dest_branch_id);
        $routeto_branch->name = $from_branch->name . '-' . $to_branch->name;
        // $from_latlng = $from_branch->location_lat . ',' . $from_branch->location_lng;
        // $to_latlng = $to_branch->location_lat . ',' . $to_branch->location_lng;
        // if (isNull($from_latlng) || isNull($to_latlng)) {
        //     $distdata  = get_distance($from_branch->district, $to_branch->district);
        // } else {
        //     $distdata  = get_distance($from_latlng, $to_latlng);
        // }
        // $routeto_branch->distance = $distdata['distance'];
        // $routeto_branch->duration = $distdata['duration'];
    }

    public function updating(Routeto_branch $routeto_branch)
    {
        $routeto_branch->updated_by = auth()->user()->id;
        $from_branch = Branch::find($routeto_branch->branch_id);
        $to_branch = Branch::find($routeto_branch->dest_branch_id);
        $routeto_branch->name = $from_branch->name . '-' . $to_branch->name;
        //$from_latlng = $from_branch->location_lat . ',' . $from_branch->location_lng;
        // $to_latlng = $to_branch->location_lat . ',' . $to_branch->location_lng;
        // $distdata  = get_distance($from_latlng, $to_latlng);
        // $routeto_branch->distance = $distdata['distance'];
        // $routeto_branch->duration = $distdata['duration'];
    }
}
