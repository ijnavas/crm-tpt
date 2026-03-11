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

        $service = new DashboardService();

        $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'dashboard' => $service->getDashboardData(),
        ]);
    }
}