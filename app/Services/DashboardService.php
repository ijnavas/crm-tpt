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
            'upcomingTasks' => $repo->getUpcomingTasks(8),
            'recentCompanies' => $repo->getRecentCompanies(5),
            'recentActivity' => $repo->getRecentActivity(10),
        ];
    }
}