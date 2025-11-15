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
        
        // Check for exact matches first
        $callback = $this->routes[$method][$path] ?? null;
        
        // If no exact match, check for dynamic routes
        if (!$callback) {
            $callback = $this->matchDynamicRoute($path, $method);
        }

        if (!$callback) {
            http_response_code(404);
            echo "<h1>404 Not Found</h1>";
            return;
        }

        // Handle controller method callbacks
        if (is_array($callback)) {
            $controller = new $callback[0]();
            $methodName = $callback[1];
            
            // Check if we have route parameters (they will be at index 2)
            if (isset($callback[2]) && is_array($callback[2])) {
                // Extract only the values (not the named keys) for positional parameters
                $parameters = array_values($callback[2]);
                echo call_user_func_array([$controller, $methodName], $parameters);
            } else {
                echo call_user_func([$controller, $methodName]);
            }
        } else {
            echo call_user_func($callback);
        }
    }
    
    private function matchDynamicRoute(string $path, string $method) {
        foreach ($this->routes[$method] as $route => $callback) {
            // Convert route pattern to regex
            $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";
            
            if (preg_match($pattern, $path, $matches)) {
                // Remove the full match (index 0) and keep only parameter values
                array_shift($matches);
                
                // If it's a controller callback, add parameters
                if (is_array($callback)) {
                    return [$callback[0], $callback[1], $matches];
                }
                
                return $callback;
            }
        }
        
        return null;
    }
    
    public function redirect(string $path)
    {
        header("Location: $path");
        exit;
    }
}