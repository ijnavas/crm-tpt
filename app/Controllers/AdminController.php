<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Repositories\UserRepository;

final class AdminController extends Controller
{
    private UserRepository $repo;

    public function __construct()
    {
        $this->repo = new UserRepository();
    }

    private function guard(): void
    {
        if (!Auth::check()) $this->redirect('/login');
        if (!$this->repo->isAdmin(Auth::id())) $this->redirect('/dashboard');
    }

    public function users(): void
    {
        $this->guard();
        $this->view('admin/users', [
            'title' => 'Gestión de usuarios',
            'users' => $this->repo->getAll(),
            'roles' => $this->repo->getRoles(),
        ]);
    }

    public function createUser(): void
    {
        $this->guard();
        $this->view('admin/user_form', [
            'title'  => 'Nuevo usuario',
            'user'   => [],
            'roles'  => $this->repo->getRoles(),
            'action' => '/admin/users/store',
        ]);
    }

    public function storeUser(): void
    {
        $this->guard();
        $data = Request::all();

        if (empty($data['password'])) {
            Session::flash('error', 'La contraseña es obligatoria');
            $this->redirect('/admin/users/create');
        }

        $this->repo->insert($data);
        Session::flash('success', 'Usuario creado correctamente');
        $this->redirect('/admin/users');
    }

    public function editUser(string $id): void
    {
        $this->guard();
        $this->view('admin/user_form', [
            'title'  => 'Editar usuario',
            'user'   => $this->repo->findById((int) $id),
            'roles'  => $this->repo->getRoles(),
            'action' => '/admin/users/' . $id . '/update',
        ]);
    }

    public function updateUser(string $id): void
    {
        $this->guard();
        $data = Request::all();
        $this->repo->update((int) $id, $data);

        if (!empty($data['new_password'])) {
            $this->repo->updatePassword((int) $id, password_hash($data['new_password'], PASSWORD_DEFAULT));
        }

        Session::flash('success', 'Usuario actualizado correctamente');
        $this->redirect('/admin/users');
    }

    public function toggleUser(string $id): void
    {
        $this->guard();

        // No permitir desactivarse a sí mismo
        if ((int) $id === Auth::id()) {
            Session::flash('error', 'No puedes desactivar tu propia cuenta');
            $this->redirect('/admin/users');
        }

        $this->repo->toggleStatus((int) $id);
        Session::flash('success', 'Estado del usuario actualizado');
        $this->redirect('/admin/users');
    }
}