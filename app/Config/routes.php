<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\LeadController;
use App\Controllers\CompanyController;
use App\Controllers\ContactController;
use App\Controllers\TaskController;
use App\Controllers\SearchController;
use App\Controllers\UserController;

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
['GET', '/companies', [CompanyController::class, 'index']],
['GET', '/companies/create', [CompanyController::class, 'create']],
['POST', '/companies/store', [CompanyController::class, 'store']],
['GET', '/companies/{id}', [CompanyController::class, 'show']],
['GET', '/companies/{id}/edit', [CompanyController::class, 'edit']],
['POST', '/companies/{id}/update', [CompanyController::class, 'update']],

['GET', '/contacts', [ContactController::class, 'index']],
['GET', '/contacts/create', [ContactController::class, 'create']],
['POST', '/contacts/store', [ContactController::class, 'store']],
['GET', '/contacts/{id}', [ContactController::class, 'show']],
['GET', '/contacts/{id}/edit', [ContactController::class, 'edit']],
['POST', '/contacts/{id}/update', [ContactController::class, 'update']],

['GET', '/tasks', [TaskController::class, 'index']],
['GET', '/tasks/create', [TaskController::class, 'create']],
['POST', '/tasks/store', [TaskController::class, 'store']],
['GET', '/tasks/{id}', [TaskController::class, 'show']],
['GET', '/tasks/{id}/edit', [TaskController::class, 'edit']],
['POST', '/tasks/{id}/update', [TaskController::class, 'update']],
['POST', '/tasks/{id}/complete', [TaskController::class, 'complete']],

['GET', '/leads/{id}/convert', [LeadController::class, 'convertForm']],
['POST', '/leads/{id}/convert', [LeadController::class, 'convert']],

['GET', '/search', [SearchController::class, 'search']],

['GET', '/profile', [UserController::class, 'profile']],
['POST', '/profile/update', [UserController::class, 'updateProfile']],

];