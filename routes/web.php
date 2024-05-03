<?php

use App\Controllers\HomeController;
use App\Controllers\AboutController;
use Kurumi\Routes\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [AboutController::class,'index']);

Route::run();
