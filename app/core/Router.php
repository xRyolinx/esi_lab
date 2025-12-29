<?php

class Router
{
    private array $routes = [];
    private string $controllerPath = __DIR__ . '/../Controllers/';

    // Enregistrer une route
    private function add(string $method, string $path, string $action, $middlewares = [])
    {
        $method = strtoupper($method);
        $pattern = preg_replace('#\{([\w]+)\}#', '(?P<$1>[\w-]+)', $path);
        $pattern = "#^$pattern/?$#";

        [$controller, $action] = explode(':', $action);

        $this->routes[$method][$pattern] = [$controller, $action, $middlewares];
    }


    // methodes requetes
    public function get(string $path, string $action, $middlewares = [])
    {
        $this->add('GET', $path, $action, $middlewares);
    }
    public function post(string $path, string $action, $middlewares = [])
    {
        $this->add('POST', $path, $action, $middlewares);
    }
    public function put(string $path, string $action, $middlewares = [])
    {
        $this->add('PUT', $path, $action, $middlewares);
    }
    public function patch(string $path, string $action, $middlewares = [])
    {
        $this->add('PATCH', $path, $action, $middlewares);
    }
    public function delete(string $path, string $action, $middlewares = [])
    {
        $this->add('DELETE', $path, $action, $middlewares);
    }


    public function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Method override
        if ($method === 'POST' && isset($_POST['_method'])) {
            $override = strtoupper($_POST['_method']);
            if (in_array($override, ['DELETE', 'PUT', 'PATCH'])) {
                $method = $override;
            }
        }

        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        foreach ($this->routes[$method] as $pattern => [$controller, $action, $middlewares]) {
            if (preg_match($pattern, $uri, $matches)) {
                // Middlewares
                foreach ($middlewares as $middleware) {
                    $middleware();
                }

                $params = array_filter($matches, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);

                require_once __DIR__ . "/../Controllers/$controller.php";
                $controllerInstance = new $controller();

                call_user_func_array([$controllerInstance, $action], $params);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
