<?php

/**
 * @file
 * Routing file for app.
 */

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ModelNumberController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/it/laptop-checkout', function () {
    return view('welcome');
});

// Authentication.
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Admin users.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::resource('users', UserController::class)->except(['show']);
});

// Activities.
Route::middleware(['auth', 'can.edit'])->group(function () {
  Route::controller(ActivityController::class)->group(function () {
    Route::get('/log', 'logActivity');
    Route::post('/log', 'store');
    Route::patch('/{activity}', 'patch')->name('activities.patch');
    Route::get('/activity/edit/{activity}', 'edit');
    Route::delete('/activity/delete/{activity}', 'delete')->name('activities.delete');
  });
});

// Devices.

Route::middleware(['auth', 'can.edit'])->group(function () {
  Route::controller(DeviceController::class)->group(function () {
    Route::patch('/device/{device}', 'patch')->name('devices.patch');
    Route::get('/device/edit/{device}', 'edit');
    Route::delete('/device/delete/{device}', 'delete')->name('devices.delete');
  });
});

// Search.
Route::middleware(['auth'])->group(function () {
  Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'search')->name('search');
  });
});

// Reports.
Route::middleware(['auth'])->group(function () {
  Route::controller(ReportsController::class)->group(function () {
    Route::get('/reports', 'reports');
  });
});

// API autocomplete.
Route::middleware(['auth', 'can.edit'])->group(function () {
  Route::get('/api/model-numbers/search', [ModelNumberController::class, 'search']);
});

// Export.
Route::middleware(['auth'])->group(function () {
  Route::controller(ExportController::class)->group(function () {
    Route::get('/export/activities', 'activities')->name('export.activities');
    Route::get('/export/devices', 'devices')->name('export.devices');
  });
});

// Errors.
Route::any('{catchall}', [PageController::class, 'notfound'])->where('catchall', '.*');
