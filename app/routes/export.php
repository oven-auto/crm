<?php

use App\Http\Controllers\Api\v1\Back\Car\CarExcelController;
use App\Http\Controllers\Api\v1\Back\Trafic\TraficExportController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Credit\CreditExcelController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Reserve\ReserveExcelController;
use App\Http\Controllers\Api\v1\Back\Worksheet\Modules\Service\ServiceExcelController;
use Illuminate\Support\Facades\Route;

Route::prefix('')->middleware(['userfromtoken'])->group(function () {
    Route::get('trafic',   [TraficExportController::class,     'index']);
    Route::get('cars',      [CarExcelController::class,         'index']);
    Route::get('reserves', [ReserveExcelController::class, 'index']);
    Route::get('services', [ServiceExcelController::class, 'index']);
    Route::get('credits', [CreditExcelController::class, 'index']);
});