<?php

use App\Http\Controllers\Admin\FlaggedDeviceController;
use App\Http\Controllers\Admin\LibraryComparisonController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PoolController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SupportCategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:users.admin'])->group(function () {
  Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('roles', RoleController::class)->except(['show']);
  });
});

Route::middleware(['auth', 'permission:laptops.admin'])->group(function () {
  Route::prefix('admin')->name('admin.')->group(function () {
    Route::prefix('library-comparison')->name('library_comparison.')->group(function () {
      Route::get('/', [LibraryComparisonController::class, 'index'])->name('index');
      Route::post('/compare', [LibraryComparisonController::class, 'compare'])->name('compare');
      Route::get('/recompare', [LibraryComparisonController::class, 'reCompare'])->name('recompare');
      Route::post('/update-status', [LibraryComparisonController::class, 'updateStatus'])->name('update-status');
      Route::post('/add-device', [LibraryComparisonController::class, 'addDevice'])->name('add-device');
      Route::post('/flag-device', [LibraryComparisonController::class, 'flagDevice'])->name('flag-device');
      Route::post('/update-all', [LibraryComparisonController::class, 'updateAll'])->name('update-all');
      Route::post('/flag-all', [LibraryComparisonController::class, 'flagAll'])->name('flag-all');
      Route::get('/reset', [LibraryComparisonController::class, 'reset'])->name('reset');
    });

    Route::prefix('flagged-devices')->name('flagged_devices.')->group(function () {
      Route::get('/', [FlaggedDeviceController::class, 'index'])->name('index');
      Route::delete('/bulk-destroy', [FlaggedDeviceController::class, 'bulkDestroy'])->name('bulk_destroy');
      Route::delete('/{device}', [FlaggedDeviceController::class, 'destroy'])->name('destroy');
    });
  });

  Route::prefix('taxonomy')->name('taxonomy.')->group(function () {
    Route::resource('status', StatusController::class);
    Route::post('status/reorder', [StatusController::class, 'reorder'])->name('status.reorder');
    Route::resource('pool', PoolController::class);
    Route::post('pool/reorder', [PoolController::class, 'reorder'])->name('pool.reorder');
    Route::resource('support_category', SupportCategoryController::class);
    Route::post('support_category/reorder', [SupportCategoryController::class, 'reorder'])->name('support_category.reorder');
  });

});
