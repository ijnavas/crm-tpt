<?php
declare(strict_types=1);

namespace App\Core;

final class Response
{
    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
