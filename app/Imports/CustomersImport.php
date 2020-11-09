<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;

class CustomersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Customer([
            'customer_code' => $row[0],
            'name'     => $row[1],
            'address' => $row[2],
            'sub_district' => $row[3],
            'district' => $row[4],
            'province' => $row[5],
            'postal_code' => $row[6],
            'phoneno' => $row[7],
        ]);
    }
}
