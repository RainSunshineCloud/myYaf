<?php
use RainSunshineCloud\JWT;
use RainSunshineCloud\JWTException;
use RainSunshineCloud\Request;
use RainSunshineCloud\RequestException;

define('APP_PATH', dirname(dirname(__FILE__)));
include APP_PATH.'/vendor/autoload.php';
$application = new Yaf\Application( APP_PATH . "/conf/application.ini");
$application->bootstrap()->run();


