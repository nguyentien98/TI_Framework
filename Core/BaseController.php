<?php

namespace Core;

class BaseController
{
    const VIEW_FOLDER = '/Views';

    public function view($view, $params = [])
    {
        $folder = constant('FRAMEWORK_ROOT') . self::VIEW_FOLDER . '/';
        $file = str_replace('.', '/', $view);
        $fullPath = $folder . $file . '.php';
        if (file_exists($fullPath)) {
            extract($params);
            ob_start();
            include $fullPath;
            $fileContent = ob_get_clean();
            echo $fileContent;
        } else {
            echo 'View does not exists';
        }
    }
}
