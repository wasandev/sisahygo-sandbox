<?php

namespace App\Rules;

use App\Models\District;
use App\Models\Province;
use Illuminate\Contracts\Validation\Rule;

class CheckDistrict implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $checkdistrict  = District::join('province', 'province.id', '=', 'district.province_id')
            ->where('district.name', '=', $value)
            ->first();

        return isset($checkdistrict);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'กรุณาตรวจสอบชื่ออำเภอของลูกค้าให้ถูกต้อง';
    }
}
