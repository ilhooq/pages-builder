<?php
use piko\Application;
use piko\Utils;

require(__DIR__ . '/vendor/autoload.php');

if (file_exists(__DIR__ . '/.env')) {
    Utils::parseEnvFile(__DIR__ . '/.env');
}

$config = require __DIR__ . '/config.php';

(new Application($config))->run();