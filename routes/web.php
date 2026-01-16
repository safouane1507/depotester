<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// --- Espace INVITE (Public) ---
Route::get('/', [DashboardController::class, 'guestIndex'])->name('guest.index');
Route::get('/resources/{id}', [DashboardController::class, 'resourceDetail']);
Route::get('/register-request', [DashboardController::class, 'showRegisterForm']);

// --- Espaces Sécurisés ---
Route::middleware(['auth'])->group(function () {

    // Utilisateur Interne
    Route::middleware(['role:user'])->prefix('user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
        // Routes réservations...
    });

    // Responsable Technique
    Route::middleware(['role:manager'])->prefix('manager')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
        // Gestion des ressources...
    });

    // Administrateur
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        // Validation comptes, stats globales...
    });
});