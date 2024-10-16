<?php

use App\TimeTableCommand;
use Symfony\Component\Console\Application;

define(
    'APP_START',
    microtime(true)
);

// Register the Composer autoloader...
require_once __DIR__ . '/vendor/autoload.php';

$consoleApp = new Application();

$consoleApp->add(new TimeTableCommand());

$consoleApp->run();
