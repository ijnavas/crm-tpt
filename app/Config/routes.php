<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\LeadController;

return [
    ['GET', '/', [AuthController::class, 'loginForm']],
    ['GET', '/login', [AuthController::class, 'loginForm']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['POST', '/logout', [AuthController::class, 'logout']],

    ['GET', '/dashboard', [DashboardController::class, 'index']],

    ['GET', '/leads', [LeadController::class, 'index']],
    ['GET', '/leads/create', [LeadController::class, 'create']],
    ['POST', '/leads/store', [LeadController::class, 'store']],
    ['GET', '/leads/{id}', [LeadController::class, 'show']],
    ['GET', '/leads/{id}/edit', [LeadController::class, 'edit']],
    ['POST', '/leads/{id}/update', [LeadController::class, 'update']],
    ['POST', '/leads/{id}/notes', [LeadController::class, 'storeNote']],
    ['POST', '/leads/{id}/status', [LeadController::class, 'updateStatus']],
];
