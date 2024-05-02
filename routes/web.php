<?php

use App\Controllers\HomeController;
use Kurumi\Routes\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/about', function() {
    return view('about/index', ["nama" => "kurumi"]);
});

Route::run();
