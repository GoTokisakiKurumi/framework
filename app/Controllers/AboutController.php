<?php

namespace App\Controllers;

class AboutController {

    public static function index()
    {
        return view("about/index", [
            "nama" => "kurumi"
        ]);
    } 
}
