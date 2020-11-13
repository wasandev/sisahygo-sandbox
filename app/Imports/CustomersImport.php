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
            'taxid' => $row[2],
            'address' => $row[3],
            'sub_district' => $row[4],
            'district' => $row[5],
            'province' => $row[6],
            'postal_code' => $row[7],
            'phoneno' => $row[8],
            'paymenttype' => $row[9]
        ]);
    }
}
