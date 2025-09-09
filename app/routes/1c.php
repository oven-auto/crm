<?php

use App\Http\Controllers\Api\v1\For1C\Client\ClientCarController;
use App\Http\Controllers\Api\v1\For1C\Client\ClientController;
use Illuminate\Support\Facades\Route;

Route::prefix('clients')->group(function () {
    Route::get('find', [ClientController::class, 'find']);
});

Route::prefix('client_cars')->group(function () {
    Route::get('find', [ClientCarController::class, 'find']);
});