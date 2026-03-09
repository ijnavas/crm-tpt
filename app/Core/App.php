<?php
declare(strict_types=1);

namespace App\Core;

final class App
{
    public function run(): void
    {
        $router = new Router();
        $routes = require app_path('Config/routes.php');

        foreach ($routes as [$method, $uri, $action]) {
            $router->add($method, $uri, $action);
        }

        $router->dispatch(Request::method(), Request::path());
    }
}
