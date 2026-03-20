<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Services\DashboardService;

final class DashboardController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $user    = Auth::user();
        $role    = $user['role_name'] ?? $user['role_id'] ?? null;
        $service = new DashboardService();

        match ($role) {
            'direccion' => $this->view('dashboard/direccion', [
                'title'     => 'Dashboard Dirección',
                'dashboard' => $service->getDashboardData(),
            ]),
            'comercial' => $this->view('dashboard/comercial', [
                'title'     => 'Dashboard Comercial',
                'dashboard' => $service->getDashboardData(),
            ]),
            default => $this->view('dashboard/index', [
                'title'     => 'Dashboard',
                'dashboard' => $service->getDashboardData(),
            ]),
        };
    }
}