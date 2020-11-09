<?php

namespace App\Imports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\ToModel;

class BranchesImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Branch([
            'code' => $row[0],
            'name'     => $row[1],
            'address' => $row[2],
            'sub_district' => $row[3],
            'district' => $row[4],
            'province' => $row[5],
            'postcode' => $row[6],
            'country' => $row[7],
            "location_lat" => $row[8],
            "location_lng" => $row[9],
            'phoneno' => $row[10],
            'type' => $row[11]



        ]);
    }
}
