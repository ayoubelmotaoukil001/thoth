<?php

class Router
{
    private $routes = [];

    public function get($path, $controller, $method)
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function post($path, $controller, $method)
    {
        $this->routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Match dynamic routes
        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                
                $controllerName = $handler['controller'];
                $methodName = $handler['method'];

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    if (method_exists($controller, $methodName)) {
                        call_user_func_array([$controller, $methodName], $matches);
                        return;
                    }
                }
            }
        }

        // 404
        http_response_code(404);
        echo "404 - Page not found";
    }
}