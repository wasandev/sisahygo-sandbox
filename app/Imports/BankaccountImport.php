<?php

namespace App\Imports;

use App\Models\Bankaccount;
use Maatwebsite\Excel\Concerns\ToModel;

class BankaccountImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Bankaccount([
            'account_no'   => $row[0],
            'account_name'   => $row[1],
            'bankbranch'   => $row[2],
        ]);
    }
}
