<?php

use App\Controllers\HomeController;
use Kurumi\Routes\Route;

Route::get('/', [HomeController::class, 'index']);

Route::run();
