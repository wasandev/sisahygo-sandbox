<?php

namespace App\Imports;

use App\Models\Product_style;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductstylesImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Product_style([
            'name'     => $row[0],
        ]);
    }
}
