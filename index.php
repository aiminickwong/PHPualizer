<?php
require_once 'vendor/autoload.php';

date_default_timezone_set(\PHPualizer\Config::getConfigData()['timezone']);

@session_start();

$router = new \PHPualizer\Routes;
$router->startRouter();
