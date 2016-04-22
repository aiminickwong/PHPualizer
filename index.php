<?php
require_once 'vendor/autoload.php';

date_default_timezone_set(\PHPualizer\Util\Config::getConfigData()['timezone']);

set_error_handler('\PHPualizer\Util\ErrorHandlers::session');
session_start();
restore_error_handler();

$router = new \PHPualizer\Routes;
$router->startRouter();
