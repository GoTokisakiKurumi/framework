<?php

// Memanggil utils function...
require_once __DIR__ . '/../../src/kurumi/Utils/func/init.php';


use Kurumi\Container\Container;
use Kurumi\Utils\View;
use Kurumi\KurumiTemplates\KurumiTemplate;
use Kurumi\KurumiTemplates\KurumiTransform;
use Kurumi\KurumiTemplates\KurumiTransformInterface;

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

$container->bind('View', fn () => new View(PATH_STORAGE));
$container->bind('KurumiTemplate', function($container) {
    return new KurumiTemplate($container->make('KurumiTransform'));
});
$container->bind('KurumiTransform', fn () => new KurumiTransform(PATH_VIEWS));
$container->bind(KurumiTransformInterface::class, function() {
    return new KurumiTransform(PATH_VIEWS);
});

return $container;
