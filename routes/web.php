<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DeviceController;

Route::controller(ActivityController::class)->group(function () {
  Route::get('/', 'logActivity');
  Route::post('/', 'store');
});

Route::controller(DeviceController::class)->group(function () {
  Route::get('/device/{device}/edit', 'edit');
});

Route::view('/search', 'search');
Route::view('/reports', 'reports');
