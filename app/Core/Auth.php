<?php
declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function check(): bool
    {
        return Session::get('auth.user_id') !== null;
    }

    public static function id(): ?int
    {
        $id = Session::get('auth.user_id');
        return $id !== null ? (int) $id : null;
    }

    public static function user(): ?array
    {
        return Session::get('auth.user');
    }

    public static function login(array $user): void
    {
        Session::put('auth.user_id', (int) $user['id']);
        Session::put('auth.user', $user);
    }

    public static function logout(): void
    {
        Session::forget('auth.user_id');
        Session::forget('auth.user');
    }
}
