<?php
namespace App\Core;

class Router {
    private array $routes = [];

    public function get(string $path, $callback): void {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, $callback): void {
        $this->routes['POST'][$path] = $callback;
    }

    public function delete(string $path, $callback): void {
        $this->routes['DELETE'][$path] = $callback;
    }

    public function dispatch(string $uri, string $method): void {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $callback = $this->routes[$method][$path] ?? null;

        if (!$callback) {
            http_response_code(404);
            echo "<h1>404 Not Found</h1>";
            return;
        }

        // Handle controller method callbacks
        if (is_array($callback)) {
            $controller = new $callback[0]();
            $method = $callback[1];
            echo call_user_func([$controller, $method]);
        } else {
            echo call_user_func($callback);
        }
    }
    
    public function redirect(string $path)
    {
        header("Location: $path");
        exit;
    }
}