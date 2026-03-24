<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Repositories\StatsRepository;

final class StatsController extends Controller
{
    public function conversion(): void
    {
        if (!Auth::check()) $this->redirect('/login');

        $repo = new StatsRepository();

        $this->view('stats/conversion', [
            'title'      => 'Estadísticas de conversión',
            'kpis'       => $repo->getGlobalKpis(),
            'bySource'   => $repo->getConversionBySource(),
            'byService'  => $repo->getConversionByService(),
            'byUser'     => $repo->getConversionByUser(),
            'byStatus'   => $repo->getStatusDistribution(),
            'perMonth'   => $repo->getLeadsPerMonth(),
        ]);
    }
}