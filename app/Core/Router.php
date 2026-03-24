<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function add(string $method, string $uri, array $action): void
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $uri);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => $pattern,
            'action'  => $action,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }

            if (preg_match($route['pattern'], $path, $matches)) {
                [$controllerClass, $controllerMethod] = $route['action'];
                $controller = new $controllerClass();

                // Extraer solo parámetros con nombre (no numéricos)
                $params = array_filter(
                    $matches,
                    static fn($key) => !is_int($key),
                    ARRAY_FILTER_USE_KEY
                );

                // Usar reflexión para pasar parámetros en el orden correcto
                $ref = new \ReflectionMethod($controllerClass, $controllerMethod);
                $ordered = [];
                foreach ($ref->getParameters() as $param) {
                    $ordered[] = $params[$param->getName()] ?? null;
                }

                call_user_func_array([$controller, $controllerMethod], $ordered);
                return;
            }
        }

        http_response_code(404);
        echo View::render('errors/404', ['title' => '404']);
    }
}