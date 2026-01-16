<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

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
    });

    // Manager
    Route::middleware(['role:manager'])->prefix('manager')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
    });

    // Admin
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    });

    // Utilisateur Interne
    Route::middleware(['role:user'])->prefix('user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
        
        // --- ROUTES AJOUTÉES ---
        // Afficher le formulaire
        Route::get('/reservations/create', [App\Http\Controllers\ReservationController::class, 'create'])->name('reservations.create');
        // Enregistrer la demande
        Route::post('/reservations', [App\Http\Controllers\ReservationController::class, 'store'])->name('reservations.store');
    });

    Route::middleware(['role:manager'])->prefix('manager')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])->name('manager.dashboard');
        
        // --- ROUTE AJOUTÉE ---
        Route::post('/reservations/{id}/handle', [App\Http\Controllers\ReservationController::class, 'handleRequest'])->name('manager.reservations.handle');
    });

    // Administrateur
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // --- ROUTES AJOUTÉES ---
        // Activer un utilisateur
        Route::post('/users/{id}/activate', [App\Http\Controllers\AdminController::class, 'activateUser'])->name('admin.users.activate');
        
        // Ajouter une ressource
        Route::post('/resources', [App\Http\Controllers\AdminController::class, 'storeResource'])->name('admin.resources.store');
    });

    
});