<?php

namespace Core\Routing;

class Route
{
    public $path;
    public $requestMethod;
    public $controller;
    public $function;
    public $name;

    public function __construct($path, $requestMethod, $controller, $function)
    {
        $this->path = $path;
        $this->requestMethod = $requestMethod;
        $this->controller = $controller;
        $this->function = $function;
    }

    public function name($name)
    {
        $this->name = $name;
    }

    public function getUrl()
    {
        $root = dirname(__FILE__);

        return $root . '/' . $this->path;
    }
}
