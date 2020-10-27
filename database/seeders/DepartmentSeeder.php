<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;


class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * ประเภทสินค้า.
         */

        $data = [
            'ฝ่ายการเงิน', 'ฝ่ายการตลาด', 'ฝ่ายขนส่ง', 'ฝ่ายขาย',
            'ฝ่ายคลังสินค้า', 'ฝ่ายจัดซื้อ', 'ฝ่ายธุรการ', 'ฝ่ายบัญชี', 'ฝ่ายบุคคล',
            'ฝ่ายไอที'
        ];


        foreach ($data as $data_department) {
            $department = new Department();
            $department->name = $data_department;
            $department->save();
        }
    }
}
