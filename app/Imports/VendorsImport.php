<?php

namespace App\Imports;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\ToModel;

class VendorsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Vendor([
            'owner_code' => $row[0],
            'name' => $row[1],
            'taxid' => $row[2],
            'address' => $row[3],
            'phoneno' => $row[4],
            'type' => $row[5]

        ]);
    }
}
