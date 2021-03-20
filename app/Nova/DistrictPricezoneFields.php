<?php

namespace App\Nova;

use Laravel\Nova\Fields\Currency;


class DistrictPricezoneFields
{
    /**
     * Get the pivot fields for the relationship.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            Currency::make('ค่าธรรมเนียม Express', 'express_fee'),
            Currency::make('ค่าธรรมเนียมระยะไกล', 'faraway_fee')
        ];
    }
}
