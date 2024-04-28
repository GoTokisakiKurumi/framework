<?php

use Whoops\Handler\PrettyPageHandler;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/kurumi/utils/init.php';


$whoops = new \Whoops\Run;
$handler = new PrettyPageHandler();

$whoops->pushHandler($handler);
$whoops->register();



require_once __DIR__ . '/../routes/web.php';
