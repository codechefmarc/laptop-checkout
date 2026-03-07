<?php

/**
 * @file
 * Default routes.
 */

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'))->name('welcome');
Route::get('/it/laptop-checkout', fn() => view('welcome'));
