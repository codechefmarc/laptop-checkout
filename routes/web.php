<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;

Route::controller(ActivityController::class)->group(function () {
  Route::get('/', 'logActivity');
});

Route::view('/search', 'search');
Route::view('/reports', 'reports');
