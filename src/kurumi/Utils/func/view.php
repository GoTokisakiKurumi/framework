<?php

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
    $container->make("View")->render($path, $data);
}
