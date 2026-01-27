<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResourceManagerController;

// --- ACCÈS PUBLIC ---
Route::get('/', [DashboardController::class, 'guestIndex'])->name('guest.index');
Route::get('/resources/{id}', [DashboardController::class, 'resourceDetail'])->name('resource.show');

// --- AUTHENTIFICATION ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- INSCRIPTION ---
Route::get('/register-request', [DashboardController::class, 'showRegisterForm'])->name('register.request');
Route::post('/register-request', [AuthController::class, 'register']);

// --- ESPACES SÉCURISÉS ---
Route::middleware(['auth'])->group(function () {
    
    // User
    Route::middleware(['role:user'])->prefix('user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
        Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
        
        // Routes pour demandes personnalisées (Custom Requests)
        Route::get('/custom-request', [ReservationController::class, 'createCustom'])->name('user.custom.create');
        Route::post('/custom-request', [ReservationController::class, 'storeCustom'])->name('user.custom.store');
    });

    // Manager
    Route::middleware(['role:manager'])->prefix('manager')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
        Route::post('/reservations/{id}/handle', [ReservationController::class, 'handleRequest'])->name('manager.reservations.handle');
        
        // Gestion complète des ressources (CRUD)
        Route::resource('resources', ResourceManagerController::class)->names('manager.resources');
        
        // Anciennes routes de maintenance (toujours compatibles)
        Route::get('/resources/{id}/edit', [DashboardController::class, 'editResource'])->name('manager.resources.edit');
        Route::post('/resources/{id}', [DashboardController::class, 'updateResource'])->name('manager.resources.update');

        // Routes pour gérer les demandes sur mesure (Custom Requests)
        Route::post('/custom-requests/{id}/approve', [App\Http\Controllers\ResourceManagerController::class, 'approveCustom'])->name('manager.custom.approve');
        Route::post('/custom-requests/{id}/reject', [App\Http\Controllers\ResourceManagerController::class, 'rejectCustom'])->name('manager.custom.reject');
    });

    
    // Admin
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // CORRECTION : Route pour activer/désactiver un utilisateur
        Route::post('/users/{id}/toggle-status', [App\Http\Controllers\AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle');
        
        Route::post('/resources', [App\Http\Controllers\AdminController::class, 'storeResource'])->name('admin.resources.store');
    });
});