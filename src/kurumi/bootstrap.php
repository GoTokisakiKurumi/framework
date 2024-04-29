<?php

// Memanggil utils function...
require_once __DIR__ . '/../../src/kurumi/Utils/func/init.php';


use Kurumi\Container\Container;
use Kurumi\Utils\View;
use Kurumi\KurumiTemplates\KurumiTemplate;

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

$container->bind('View', fn () => new View(PATH_VIEWS));
$container->bind('KurumiTemplate', fn () => new KurumiTemplate());

return $container;
