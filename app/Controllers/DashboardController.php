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
        if (!Auth::check()) $this->redirect('/login');

        $user      = Auth::user();
        $dashboard = $user['dashboard'] ?? 'default';
        $service   = new DashboardService();
        $data      = $service->getDashboardData();

        match ($dashboard) {
            'comercial' => $this->view('dashboard/comercial', [
                'title' => 'Dashboard Comercial', 'dashboard' => $data,
            ]),
            'direccion' => $this->view('dashboard/direccion', [
                'title' => 'Dashboard Dirección', 'dashboard' => $data,
            ]),
            default => $this->view('dashboard/index', [
                'title' => 'Dashboard', 'dashboard' => $data,
            ]),
        };
    }
}