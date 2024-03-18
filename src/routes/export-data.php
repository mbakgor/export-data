<?php

use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['web', 'auth'], 'guard' => 'auth'], function () {
    Route::get('/plugins/export-data', [\mbakgor\ExportData\Http\Controllers\ExportDataController::class, 'index'])->name('export-data.index');
    Route::post('/plugins/export-data', [\mbakgor\ExportData\Http\Controllers\ExportDataController::class, 'exportDevices'])->name('export-data.exportDevices');
    Route::post('/plugins/export-data/export-disks', [\mbakgor\ExportData\Http\Controllers\ExportDataController::class, 'exportDisks'])->name('export-data.exportDisks');
    Route::post('/plugins/export-data/export-specific-data', [\mbakgor\ExportData\Http\Controllers\ExportDataController::class, 'exportSpecificData'])->name('export-data.exportSpecificData');
    Route::post('/plugins/export-data/export-failed-backups', [\mbakgor\ExportData\Http\Controllers\ExportDataController::class, 'exportFailedBackups'])->name('export-data.exportFailedBackups');
});
