<?php

/**
 * @file
 * Defines routes for device management.
 */

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ModelNumberController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

  Route::middleware('permission:laptops.reports')->group(function () {
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    Route::prefix('export')->name('export.')->controller(ExportController::class)->group(function () {
      Route::get('/activities', 'activities')->name('activities');
      Route::get('/devices', 'devices')->name('devices');
      Route::get('/flagged-devices', 'flaggedDevices')->name('flagged-devices');
    });
  });

  Route::middleware('permission:laptops.edit')->controller(ActivityController::class)->group(function () {
    Route::get('/log', 'logActivity')->name('log');
    Route::post('/log', 'store');
    Route::patch('/{activity}', 'patch')->name('activities.patch');
    Route::get('/activity/edit/{activity}', 'edit')->name('activities.edit');
    Route::delete('/activity/delete/{activity}', [ActivityController::class, 'delete'])->name('activities.delete');
  });

  Route::controller(DeviceController::class)->group(function () {
    Route::patch('/device/{device}', 'patch')->name('devices.patch');
    Route::get('/device/edit/{device}', 'edit')->name('devices.edit');
    Route::delete('/device/delete/{device}', 'delete')->name('devices.delete');
    Route::get('/api/model-numbers/search', [ModelNumberController::class, 'search'])->name('api.model-numbers');
  });
});

Route::any('{catchall}', [PageController::class, 'notfound'])->where('catchall', '.*');
