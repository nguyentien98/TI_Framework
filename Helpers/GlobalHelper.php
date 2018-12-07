<?php

if (!function_exists('config')) {
    function config($config)
    {
        $config = explode('.', $config);
        $file = '/config/' . array_shift($config);
        $configContent = require_once constant('FRAMEWORK_ROOT') . $file . '.php';
        
        $configValue = null;
        foreach ($config as $key => $value) {
            if (!$configValue) {
                $configValue = $configContent[$value];
            } else {
                $configValue = $configValue[$value];
            }
        }

        return $configValue;
    }
}
