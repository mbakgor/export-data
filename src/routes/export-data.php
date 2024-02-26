<?php

use Illuminate\Support\Facades\Route;

use mbakgor\ExportData\Http\Controllers\ExportDataController;

Route::group(['middleware' => ['web', 'auth'], 'guard' => 'auth'], function () {
    Route::post('/export-devices', [ExportDataController::class, 'exportDevices'])->name('export.devices');

});
