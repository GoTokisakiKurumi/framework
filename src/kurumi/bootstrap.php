<?php

// Memanggil utils function...
require_once __DIR__ . '/../../src/kurumi/Utils/func/init.php';

use Kurumi\Consoles\Command;
use Kurumi\Container\Container;
use Kurumi\View;
use Kurumi\KurumiTemplates\KurumiTemplate;
use Kurumi\KurumiTemplates\KurumiDirective;

/**
 *
 *  Handler Error Whoops Register
 *
 **/
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


/**
 *
 *  Register Container 
 *
 **/
$container = new Container();

$container->bind('View', function($container) {
    return new View($container, PATH_STORAGE);
});
$container->bind('KurumiDirective', function() { 
    return new KurumiDirective(PATH_VIEWS);
});
$container->bind('KurumiTemplate', function($container) {
    return new KurumiTemplate($container->make('KurumiDirective'));
});
$container->bind('Command', function() {
    return new Command();
});

return $container;
