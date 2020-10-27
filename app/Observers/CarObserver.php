<?php

namespace App\Observers;

use App\Models\Car;

class CarObserver
{
    public function creating(Car $car)
    {
        $car->user_id = auth()->user()->id;
        $car->status = '1';
    }

    public function updating(Car $car)
    {
        $car->updated_by = auth()->user()->id;
    }
}
