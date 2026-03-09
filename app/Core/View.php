<?php
declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $view, array $data = [], string $layout = 'app'): string
    {
        $viewFile = app_path('Views/' . $view . '.php');
        $layoutFile = app_path('Views/layouts/' . $layout . '.php');

        if (!file_exists($viewFile)) {
            throw new \RuntimeException('Vista no encontrada: ' . $viewFile);
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        ob_start();
        require $layoutFile;
        return ob_get_clean();
    }
}
