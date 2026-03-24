<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Repositories\AccessLogRepository;
use App\Repositories\UserRepository;

final class AccessLogController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) $this->redirect('/login');

        $repo    = new AccessLogRepository();
        $userRepo = new UserRepository();
        $filters = Request::all();

        $this->view('admin/access_logs', [
            'title'   => 'Log de acceso',
            'logs'    => $repo->getAll($filters),
            'filters' => $filters,
            'stats'   => $repo->getStats(),
            'users'   => $repo->getUsers(),
        ]);
    }
}