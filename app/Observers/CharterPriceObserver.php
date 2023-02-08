<?php

namespace App\Observers;

use App\Models\Branch_area;
use App\Models\Charter_price;
use App\Models\Charter_route;

class CharterPriceObserver
{
    public function creating(Charter_price $charter_price)
    {
        // $charter_route = Charter_route::find($charter_price->charter_route_id);
        // $from_district = Branch_area::find($charter_route->branch_area_id);
        // $distdata  = get_distance($from_district->district, $charter_route->to_district);
        // $charter_price->timespent = $distdata['duration'];
        $charter_price->user_id = auth()->user()->id;
    }

    public function updating(Charter_price $charter_price)
    {
        $charter_price->updated_by = auth()->user()->id;
    }
}
