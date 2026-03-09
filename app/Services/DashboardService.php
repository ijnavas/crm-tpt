<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\DashboardRepository;

final class DashboardService
{
    public function getDashboardData(): array
    {
        $repo = new DashboardRepository();

        return [
            'kpis' => $repo->getKpis(),
            'recentLeads' => $repo->getRecentLeads(5),
            'todayTasks' => $repo->getTodayTasks(5),
            'recentActivity' => $repo->getRecentActivity(8),
        ];
    }
}
