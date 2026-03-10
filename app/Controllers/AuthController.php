<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Services\AuthService;

final class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }

        $this->view('auth/login', ['title' => 'Acceso'], 'auth');
    }

    public function login(): void
    {
        $service = new AuthService();
        $email = trim((string) Request::input('email'));
        $password = (string) Request::input('password');

        if (!$service->attempt($email, $password)) {
            Session::flash('error', 'Credenciales incorrectas');
            $this->redirect('/login');
        }

        $this->redirect('/dashboard');
    }

    public function logout(): void
    {
        Auth::logout();
        Session::destroy();
        $this->redirect('/login');
    }
}