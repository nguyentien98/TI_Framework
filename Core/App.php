<?php

namespace Core;

use Core\Routing\Router;

class App
{
    private $router;

    public function start()
    {
        $this->helpers();
        $this->startRoute();
    }

    public function startRoute()
    {
        $router = new Router;
        require 'route.php';
        $router->start();
    }

    public function helpers()
    {
        require 'Helpers/GlobalHelper.php';
    }
}
