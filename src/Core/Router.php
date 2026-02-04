<?php
namespace App\Core;

class Router {
    private $routes = [];
    
    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'pattern' => $this->convertToPattern($path)
        ];
    }
    
    private function convertToPattern($path) {
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    public function dispatch($method, $uri) {
        // Debug logging
        error_log("Router: Dispatching {$method} {$uri}");
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // Remove full match
                
                list($controller, $action) = explode('@', $route['handler']);
                
                // Handle namespaced controllers
                $controllerClass = 'App\\Controllers\\' . $controller;
                
                if (!class_exists($controllerClass)) {
                    error_log("Router: Controller {$controllerClass} not found");
                    throw new \Exception("Controller {$controllerClass} not found");
                }
                
                $controllerInstance = new $controllerClass();
                
                if (!method_exists($controllerInstance, $action)) {
                    error_log("Router: Method {$action} not found in {$controllerClass}");
                    throw new \Exception("Method {$action} not found in {$controllerClass}");
                }
                
                error_log("Router: Calling {$controllerClass}@{$action}");
                return call_user_func_array([$controllerInstance, $action], $matches);
            }
        }
        
        error_log("Router: No route found for {$method} {$uri}");
        throw new \Exception("Route not found");
    }
    
    public function getRoutes() {
        return $this->routes;
    }
}
