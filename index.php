<?php

define('FRAMEWORK_ROOT', dirname(__FILE__));

require_once 'autoload.php';

$errorHandler = new Core\ErrorHandling;
$errorHandler->handling();

$app = new Core\App();
$app->start();
