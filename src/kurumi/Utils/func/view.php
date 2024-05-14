<?php

use Kurumi\Views\View;

/**
 *
 *  @param string $path
 *  @patam array $data 
 *
 *  @author Lutfi Aulia Sidik 
 *
 **/
function view(string $path, array $data = [])
{
    global $container;
    $container->make(View::class)->render($path, $data);
}
