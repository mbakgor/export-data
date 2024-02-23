<?php

use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['web', 'auth'], 'guard' => 'auth'], function () {
    Route::get('/plugins/export-data', [\mbakgor\ExportData\Http\Controllers\ExportDataController::class, 'index'])->name('export-data.index');
    Route::post('/plugins/export-data', [\mbakgor\ExportData\Http\Controllers\ExportDataController::class, 'post_request'])->name('export-data.post_request');
    Route::post('/plugins/export-data/get-device-group', [\mbakgor\ExportData\Http\Controllers\ExportDataController::class, 'get_device_groups'])->name('export-data.get_device_groups');
});
