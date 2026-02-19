<?php

/**
 * @file
 * Routing file for app.
 */

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\FlaggedDeviceController;
use App\Http\Controllers\Admin\LibraryComparisonController;
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
})->name('welcome');

// Authentication.
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Admin users.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::resource('users', UserController::class)->except(['show']);

  Route::prefix('library-comparison')->name('library_comparison.')->group(function () {
    Route::get('/', [LibraryComparisonController::class, 'index'])->name('index');
    Route::post('/compare', [LibraryComparisonController::class, 'compare'])->name('compare');
    Route::get('/recompare', [LibraryComparisonController::class, 'reCompare'])->name('recompare');
    Route::post('/update-status', [LibraryComparisonController::class, 'updateStatus'])->name('update-status');
    Route::post('/add-device', [LibraryComparisonController::class, 'addDevice'])->name('add-device');
    Route::post('/flag-device', [LibraryComparisonController::class, 'flagDevice'])->name('flag-device');
    Route::post('/update-all', [LibraryComparisonController::class, 'updateAll'])->name('update-all');
    Route::post('/flag-all', [LibraryComparisonController::class, 'flagAll'])->name('flag-all');
  });

  Route::prefix('flagged-devices')->name('flagged_devices.')->group(function () {
    Route::get('/', [FlaggedDeviceController::class, 'index'])->name('index');
    Route::delete('/bulk-destroy', [FlaggedDeviceController::class, 'bulkDestroy'])->name('bulk_destroy');
    Route::delete('/{device}', [FlaggedDeviceController::class, 'destroy'])->name('destroy');
  });

});

// User profile - users can edit their own profile.
Route::middleware(['auth'])->group(function () {
  Route::get('/profile', [UserController::class, 'editProfile'])->name('profile.edit');
  Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

// Activities.
Route::middleware(['auth', 'is.student'])->group(function () {
  Route::controller(ActivityController::class)->group(function () {
    Route::get('/log', 'logActivity')->name('log');
    Route::post('/log', 'store');
    Route::patch('/{activity}', 'patch')->name('activities.patch');
    Route::get('/activity/edit/{activity}', 'edit')->name('activities.edit');
  });
});

// Activities for admins and editors.
Route::middleware(['auth', 'can.edit'])->group(function () {
  Route::controller(ActivityController::class)->group(function () {
    Route::delete('/activity/delete/{activity}', 'delete')->name('activities.delete');
  });
});

// Devices.
Route::middleware(['auth', 'can.edit'])->group(function () {
  Route::controller(DeviceController::class)->group(function () {
    Route::patch('/device/{device}', 'patch')->name('devices.patch');
    Route::get('/device/edit/{device}', 'edit')->name('devices.edit');
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
    Route::get('/reports', 'reports')->name('reports');
  });
});

// API autocomplete.
Route::middleware(['auth', 'can.edit'])->group(function () {
  Route::get('/api/model-numbers/search', [ModelNumberController::class, 'search'])->name('api.model-numbers');
});

// Export.
Route::middleware(['auth'])->group(function () {
  Route::controller(ExportController::class)->group(function () {
    Route::get('/export/activities', 'activities')->name('export.activities');
    Route::get('/export/devices', 'devices')->name('export.devices');
    Route::get('/export/flagged-devices', 'flaggedDevices')->name('export.flagged-devices');
  });
});

// Errors.
Route::any('{catchall}', [PageController::class, 'notfound'])->where('catchall', '.*');

