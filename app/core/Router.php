<?php

namespace app\core;

class Router
{
    private array $routes = [];

    public function get( $path,  $action)
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post( $path,  $action)
    {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $action = $this->routes[$method][$uri] ?? null;

        if (!$action) {
            http_response_code(404);
            echo '404 - Page not found';
            return;
        }

        [$controller, $methodName] = $action;

        $controller = new $controller();

        if (!method_exists($controller, $methodName)) {
            die('Method not found');
        }

        $controller->$methodName();
    }
}