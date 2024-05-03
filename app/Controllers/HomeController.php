<?php

namespace App\Controllers;

class HomeController {

    public static function index()
    {
        return view('home/index', [
            "nama" => "lutfi"
        ]);
    }
}
