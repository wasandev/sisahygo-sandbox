<?php

namespace App\Observers;

use App\Models\Branch_area;
use App\Models\Charter_route;

class CharterRouteObserver
{
    public function creating(Charter_route $charter_route)
    {
        $from_district = Branch_area::find($charter_route->branch_area_id);
        $charter_route->name = $from_district->district . ' ' . $from_district->province . '-' . $charter_route->to_district . ' ' . $charter_route->to_province;
        //$distdata  = get_distance($from_district->district, $charter_route->to_district);
        //$charter_route->distance = $distdata['distance'];
        $charter_route->user_id = auth()->user()->id;
    }

    public function updating(Charter_route $charter_route)
    {
        $charter_route->updated_by = auth()->user()->id;
    }
}
