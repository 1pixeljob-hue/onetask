<?php
class Router {
    private $routes = [];

    public function add($route, $controller, $method) {
        $this->routes[$route] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch() {
        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '/';
        $url = '/' . ltrim($url, '/');

        if (array_key_exists($url, $this->routes)) {
            $controllerName = $this->routes[$url]['controller'];
            $methodName = $this->routes[$url]['method'];

            $controller = new $controllerName();
            if (method_exists($controller, $methodName)) {
                $controller->$methodName();
            } else {
                echo "Method $methodName not found in $controllerName.";
            }
        } else {
            http_response_code(404);
            echo "404 Not Found. Return to <a href='/'>Homepage</a>";
            error_log("404 Error - Route Not Found: " . $url);
        }
    }
}
