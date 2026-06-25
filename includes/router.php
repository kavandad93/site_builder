<?php
/**
 * =============================================
 * سیستم مسیریابی
 * =============================================
 */

class Router {
    private $routes = [];
    private $not_found_callback = null;
    
    public function add($path, $callback) {
        $this->routes[$path] = $callback;
        return $this;
    }
    
    public function set404($callback) {
        $this->not_found_callback = $callback;
        return $this;
    }
    
    public function dispatch($path) {
        $path = rtrim($path, '/');
        if (empty($path)) $path = '/';
        
        foreach ($this->routes as $route => $callback) {
            if ($this->match_route($route, $path)) {
                $params = $this->extract_params($route, $path);
                if ($params !== false) {
                    return call_user_func_array($callback, $params);
                }
            }
        }
        
        if ($this->not_found_callback) {
            return call_user_func($this->not_found_callback);
        }
        
        header('HTTP/1.0 404 Not Found');
        echo '<h1>404 - صفحه یافت نشد</h1>';
        return false;
    }
    
    private function match_route($route, $path) {
        $route_parts = explode('/', trim($route, '/'));
        $path_parts = explode('/', trim($path, '/'));
        
        if (count($route_parts) !== count($path_parts)) {
            return false;
        }
        
        foreach ($route_parts as $i => $part) {
            if (strpos($part, '{') === 0 && strpos($part, '}') !== false) {
                continue;
            }
            if ($part !== $path_parts[$i]) {
                return false;
            }
        }
        
        return true;
    }
    
    private function extract_params($route, $path) {
        $route_parts = explode('/', trim($route, '/'));
        $path_parts = explode('/', trim($path, '/'));
        $params = [];
        
        foreach ($route_parts as $i => $part) {
            if (strpos($part, '{') === 0 && strpos($part, '}') !== false) {
                $params[] = urldecode($path_parts[$i]);
            }
        }
        
        return $params;
    }
}
?>