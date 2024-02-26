<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['web', 'auth'], 'guard' => 'auth'], function () {
    Route::get('/plugins/export-data', [\mbakgor\ExportData\Http\Controllers\ExportDataController::class, 'index'])->name('export-data.index');
    Route::post('/plugins/export-data', [\mbakgor\ExportData\Http\Controllers\ExportDataController::class, 'exportDevices'])->name('export-data.exportDevices');

});
