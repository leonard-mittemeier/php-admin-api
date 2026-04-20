<?php

class Router
{
    protected $routes = [];
    protected $params = [];

    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
    }

    public function put($path, $callback)
    {
        $this->addRoute('PUT', $path, $callback);
    }

    public function delete($path, $callback)
    {
        $this->addRoute('DELETE', $path, $callback);
    }

    protected function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Убираем /php-admin/public из URI, если нужно
        $base = '/php-admin/public';
        if (strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->compilePattern($route['path']);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // убираем полное совпадение
                $this->params = $matches;

                return $this->dispatch($route['callback']);
            }
        }

        $this->notFound();
    }

    protected function compilePattern($path)
    {
        // Поддержка параметров вида {id}
        $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    protected function dispatch($callback)
    {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $this->params);
        }

        if (is_string($callback)) {
            // Если это просто файл (пока поддерживаем для совместимости)
            if (file_exists(__DIR__ . '/../public/' . $callback)) {
                require __DIR__ . '/../public/' . $callback;
                return;
            }

            // Если это контроллер@метод
            if (strpos($callback, '@') !== false) {
                list($controllerName, $method) = explode('@', $callback);
                $controllerFile = __DIR__ . '/../Controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $controller = new $controllerName();
                    return call_user_func_array([$controller, $method], $this->params);
                }
            }
        }

        $this->notFound();
    }

    protected function notFound()
    {
        http_response_code(404);
        echo "404 - Страница не найдена";
        exit;
    }
}