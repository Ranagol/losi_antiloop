<?php

use Symfony\Component\Console\Application;

define(
    'APP_START',
    microtime(true)
);

// Register the Composer autoloader...
require_once __DIR__ . '/vendor/autoload.php';

$consoleApp = new Application();

// Auto add all the commands
$directoryOfCommands = __DIR__ . '/app/Commands';
$commandFiles = array_diff(
    scandir($directoryOfCommands),
    [
        '.',
        '..',
    ]
);

foreach ($commandFiles as $commandFile) {
    if (is_file($directoryOfCommands . '/' . $commandFile) === true) {
        $class = '\App\Commands\\' . basename(
            $commandFile,
            '.php'
        );

        $consoleApp->add(new $class());
    }
}

$consoleApp->run();
