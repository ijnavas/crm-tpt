<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Services\UserService;

final class UserController extends Controller
{
    private UserService $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    private function guard(): void
    {
        if (!Auth::check()) $this->redirect('/login');
    }

    public function profile(): void
    {
        $this->guard();
        $id   = Auth::id();
        $user = $this->service->getProfile($id);

        $this->view('user/profile', [
            'title'   => 'Mi perfil',
            'user'    => $user,
            'isAdmin' => $this->service->isAdmin($id),
            'logo'    => $this->service->getLogo(),
        ]);
    }

    public function updateProfile(): void
    {
        $this->guard();
        $id   = Auth::id();
        $data = Request::all();

        // Subir avatar si viene
        if (!empty($_FILES['avatar']['name'])) {
            try {
                $this->service->uploadAvatar($id, $_FILES['avatar']);
                Session::flash('success', 'Foto actualizada correctamente');
            } catch (\Throwable $e) {
                Session::flash('error', $e->getMessage());
                $this->redirect('/profile');
            }
        }

        // Actualizar datos básicos
        if (!empty($data['first_name'])) {
            $this->service->updateProfile($id, $data);
            Session::flash('success', 'Perfil actualizado correctamente');
        }

        // Cambiar contraseña
        if (!empty($data['new_password'])) {
            if ($data['new_password'] !== $data['confirm_password']) {
                Session::flash('error', 'Las contraseñas no coinciden');
                $this->redirect('/profile');
            }
            if (!$this->service->updatePassword($id, $data['current_password'], $data['new_password'])) {
                Session::flash('error', 'La contraseña actual no es correcta');
                $this->redirect('/profile');
            }
            Session::flash('success', 'Contraseña actualizada correctamente');
        }

        // Subir logo (solo admin)
        if (!empty($_FILES['logo']['name']) && $this->service->isAdmin($id)) {
            try {
                $this->service->uploadLogo($_FILES['logo']);
                Session::flash('success', 'Logo actualizado correctamente');
            } catch (\Throwable $e) {
                Session::flash('error', $e->getMessage());
            }
        }

        $this->redirect('/profile');
    }
}
