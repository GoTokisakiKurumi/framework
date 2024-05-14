<?php

// Memanggil utils function...
require_once __DIR__ . '/../../src/kurumi/Utils/func/init.php';

use Kurumi\Consoles\Command;
use Kurumi\Container\Container;
use Kurumi\Views\View;
use Kurumi\KurumiEngines\{
    KurumiTemplate,
    KurumiDirective,
};

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

// Bind class View 
$container->bind(View::class, function($container) {
    return new View(
        $container->make(KurumiTemplate::class), 
        $container->make(KurumiDirective::class),
        PATH_STORAGE
    );
});

// Bind class KurumiDirective 
$container->bind(KurumiDirective::class, function() { 
    return new KurumiDirective(PATH_VIEWS);
});

// Bind class KurumiTemplate
$container->bind(KurumiTemplate::class, function($container) {
    return new KurumiTemplate($container->make(KurumiDirective::class));
});

// Bind class Command 
$container->bind(Command::class, function() {
    return new Command();
});

return $container;
