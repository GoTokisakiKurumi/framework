<?php


use Kurumi\Container\Container;
use Kurumi\Views\View;


/**
 *
 *  @param string $path
 *  @patam array $data 
 *
 *  @author Lutfi Aulia Sidik 
 **/
function view(string $path, array $data = [])
{
    Container::getInstance()
        ->make(View::class)
        ->setBasePath(PATH_STORAGE_PUBLIC)
        ->render($path, $data);
}
