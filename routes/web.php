<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DeviceController;

Route::controller(ActivityController::class)->group(function () {
  Route::get('/', 'logActivity');
  Route::post('/', 'store');
  Route::patch('/{activity}', 'patch')->name('activities.patch');
  Route::get('/edit/{activity}', 'edit');
  Route::delete('/activity/delete/{activity}', 'delete')->name('activities.delete');
});

Route::controller(DeviceController::class)->group(function () {
  Route::delete('/device/delete/{device}', 'delete')->name('device.delete');
});

Route::view('/search', 'search');
Route::view('/reports', 'reports');
