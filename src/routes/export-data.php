<?php

use Illuminate\Support\Facades\Route;



Route::group(['middleware' => ['web', 'auth'], 'guard' => 'auth'], function () {
    Route::post('/export-devices', [ExportDataController::class, 'exportDevices'])->name('export.devices');

});
