<?php

use Kurumi\Routes\Route;

Route::get('/', function() {
    return view('home/index', ["nama" => "lutfi"]);
});

Route::get('/about', function() {
    return 'Hello World! POST';
});

Route::run();
