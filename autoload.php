<?php

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $file = constant('FRAMEWORK_ROOT') . '/' . $class . '.php';
    if (file_exists($file)) {
        include_once($file);
    }
});
