<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShipmentController;

Route::middleware(['client.apikey', 'throttle:60,1'])
    ->prefix('v1/client')
    ->group(function () {
        Route::get('/shipments', [ShipmentController::class, 'index']);
        Route::get('/shipments/{tracking_no}', [ShipmentController::class, 'show']);
    });


