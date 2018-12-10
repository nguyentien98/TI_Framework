<?php

namespace Core\Routing;

use Helpers\HttpRequest;
use Core\Routing\Route;
use ReflectionClass;
use ReflectionMethod;
use Core\Container;

class Router
{
    protected $path;
    protected $routes = [];
    protected $request;
    protected $currentRoute;

    public function __construct()
    {
        $this->request = new HttpRequest();
    }

    public function start()
    {
        $this->setPath();

        if (!$this->path) {
            throw new \Exception('Route: Url must be provided.');
        }

        if (!array_key_exists($this->path, $this->routes)) {
            throw new \Exception('Route: This url is not defined.');
        }

        $currentAction = $this->routes[$this->path];
        if (!$this->request->isMethod($currentAction->requestMethod)) {
            throw new \Exception('Route: Method is not allowed.');
        }

        $controller = 'Controllers\\' . $currentAction->controller;
        $method = $currentAction->function;
        
        if (!method_exists($controller, $method)) {
            throw new \Exception("Route: Method $method is not defined in $currentAction->controller.");
        }

        return Container::methodResolve($controller, $method);
    }

    protected function setPath()
    {
        $this->path = $this->request->path;
    }

    public function get($path, $action)
    {
        $this->addToRoutes($path, $action, 'GET');
        
        return $this;
    }

    public function post($path, $action)
    {
        $this->addToRoutes($path, $action, 'POST');

        return $this;
    }

    public function name($name)
    {
        $route = $this->currentRoute;
        $route->name($name);
        $this->routes[$route->path] = $route;
    }

    protected function addToRoutes($path, $action, $method)
    {
        $exploded = explode('@', $action);
        $controllerClass = $exploded[0];
        $controllerFunction = $exploded[1];
        $route = new Route($path, $method, $controllerClass, $controllerFunction);
        $this->currentRoute = $this->routes[$path] = $route;
    }

    public function findRouteByName($routeName)
    {
        if (count($this->routes) === 0) {
            return null;
        }

        return array_filter($this->routes, function ($route) use ($routeName) {
            return $route->name == $routeName;
        });
    }
}
