<?php

/**
 * @file
 * Provides routes for checkout management.
 */

use App\Http\Controllers\Checkouts\ActivityController;
use App\Http\Controllers\Checkouts\DeviceController;
use App\Http\Controllers\Checkouts\FlaggedDeviceController;
use App\Http\Controllers\Checkouts\LibraryComparisonController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Taxonomy\ComputerModelController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('checkouts')->name('checkouts.')->group(function () {

  Route::middleware('permission:laptops.reports')->group(function () {
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    Route::prefix('export')->name('export.')->controller(ExportController::class)->group(function () {
      Route::get('/activities', 'activities')->name('activities');
      Route::get('/devices', 'devices')->name('devices');
      Route::get('/flagged-devices', 'flaggedDevices')->name('flagged-devices');
    });
  });

  Route::middleware('permission:laptops.edit')->group(function () {
    Route::controller(ActivityController::class)->group(function () {
      Route::get('/log', 'logActivity')->name('log');
      Route::post('/log', 'store');
      Route::get('/activity/edit/{activity}', 'edit')->name('activities.edit');
      Route::patch('/activity/{activity}', 'patch')->name('activities.patch');
      Route::delete('/activity/{activity}', 'delete')->name('activities.delete');
    });

    Route::controller(DeviceController::class)->group(function () {
      Route::get('/device/edit/{device}', 'edit')->name('devices.edit');
      Route::patch('/device/{device}', 'patch')->name('devices.patch');
      Route::delete('/device/{device}', 'delete')->name('devices.delete');
    });
  });

  Route::middleware('permission:laptops.admin')->group(function () {
    Route::prefix('library-comparison')->name('library_comparison.')->controller(LibraryComparisonController::class)->group(function () {
      Route::get('/', 'index')->name('index');
      Route::post('/compare', 'compare')->name('compare');
      Route::get('/recompare', 'reCompare')->name('recompare');
      Route::post('/update-status', 'updateStatus')->name('update-status');
      Route::post('/flag-device', 'flagDevice')->name('flag-device');
      Route::post('/update-all', 'updateAll')->name('update-all');
      Route::post('/flag-all', 'flagAll')->name('flag-all');
      Route::get('/reset', 'reset')->name('reset');
    });

    Route::prefix('flagged-devices')->name('flagged_devices.')->controller(FlaggedDeviceController::class)->group(function () {
      Route::get('/', 'index')->name('index');
      Route::delete('/bulk-destroy', 'bulkDestroy')->name('bulk_destroy');
      Route::delete('/{device}', 'destroy')->name('destroy');
      Route::post('/remove-flag/{device}', 'removeFlag')->name('remove_flag');
    });
  });

});

// Computer model API routes (used by TomSelect autocomplete and inline modal).
Route::middleware('auth')->prefix('api/computer-models')->name('api.computer-models.')->controller(ComputerModelController::class)->group(function () {
  Route::get('/search', 'search')->name('search');
  Route::post('/', 'apiStore')->name('store');
});
