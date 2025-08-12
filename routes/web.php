<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

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

Route::controller(SearchController::class)->group(function () {
  Route::get('/search', 'search')->name('search');
});

Route::view('/reports', 'reports');
