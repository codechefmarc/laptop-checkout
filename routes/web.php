<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ModelNumberController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

// Activities.
Route::controller(ActivityController::class)->group(function () {
  Route::get('/', 'logActivity');
  Route::post('/', 'store');
  Route::patch('/{activity}', 'patch')->name('activities.patch');
  Route::get('/activity/edit/{activity}', 'edit');
  Route::delete('/activity/delete/{activity}', 'delete')->name('activities.delete');
});

// Devices.
Route::controller(DeviceController::class)->group(function () {
  Route::patch('/device/{device}', 'patch')->name('devices.patch');
  Route::get('/device/edit/{device}', 'edit');
  Route::delete('/device/delete/{device}', 'delete')->name('devices.delete');
});

// Search.
Route::controller(SearchController::class)->group(function () {
  Route::get('/search', 'search')->name('search');
});

// Reports.
Route::controller(ReportsController::class)->group(function () {
  Route::get('/reports', 'reports');
});

// API autocomplete.
Route::get('/api/model-numbers/search', [ModelNumberController::class, 'search']);

// Export.
Route::controller(ExportController::class)->group(function () {
  Route::get('/export/activities', 'activities')->name('export.activities');
  Route::get('/export/devices', 'devices')->name('export.devices');
});

// Errors.
Route::any('{catchall}', 'PageController@notfound')->where('catchall', '.*');
