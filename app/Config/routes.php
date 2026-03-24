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
use App\Controllers\AdminController;
use App\Controllers\AccessLogController;
use App\Controllers\StatsController;
use App\Controllers\ContractController;
use App\Controllers\WorkerController;

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
    ['GET',  '/leads/{id}/status', [LeadController::class, 'show']],
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
['GET', '/search/entities', [SearchController::class, 'entities']],

['GET', '/workers', [WorkerController::class, 'index']],
['GET', '/workers/create', [WorkerController::class, 'create']],
['POST', '/workers/store', [WorkerController::class, 'store']],
['GET', '/workers/{id}', [WorkerController::class, 'show']],
['GET', '/workers/{id}/edit', [WorkerController::class, 'edit']],
['POST', '/workers/{id}/update', [WorkerController::class, 'update']],
['POST', '/workers/{id}/assignments', [WorkerController::class, 'addAssignment']],
['POST', '/workers/{id}/assignments/{assignId}/delete', [WorkerController::class, 'deleteAssignment']],

['GET', '/contracts', [ContractController::class, 'index']],
['GET', '/contracts/create', [ContractController::class, 'create']],
['POST', '/contracts/store', [ContractController::class, 'store']],
['GET', '/contracts/{id}', [ContractController::class, 'show']],
['GET', '/contracts/{id}/edit', [ContractController::class, 'edit']],
['POST', '/contracts/{id}/update', [ContractController::class, 'update']],

['GET', '/stats/conversion', [StatsController::class, 'conversion']],

['GET', '/admin/logs', [AccessLogController::class, 'index']],

['GET', '/admin/users', [AdminController::class, 'users']],
['GET', '/admin/users/create', [AdminController::class, 'createUser']],
['POST', '/admin/users/store', [AdminController::class, 'storeUser']],
['GET', '/admin/users/{id}/edit', [AdminController::class, 'editUser']],
['POST', '/admin/users/{id}/update', [AdminController::class, 'updateUser']],
['POST', '/admin/users/{id}/toggle', [AdminController::class, 'toggleUser']],

['GET', '/profile', [UserController::class, 'profile']],
['POST', '/profile/update', [UserController::class, 'updateProfile']],

];