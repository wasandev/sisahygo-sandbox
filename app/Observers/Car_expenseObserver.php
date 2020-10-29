<?php

namespace App\Observers;

use App\Models\Car_expense;

class Car_expenseObserver
{
    public function creating(Car_expense $car_expense)
    {
        $car_expense->user_id = auth()->user()->id;
    }

    public function updating(Car_expense $car_expense)
    {
        $car_expense->updated_by = auth()->user()->id;
    }
}
