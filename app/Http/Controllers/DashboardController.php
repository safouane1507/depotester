<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\User;

class DashboardController extends Controller
{
    // --- PARTIE PUBLIQUE ---

    public function guestIndex() {
        $resources = Resource::where('status', 'available')->get();
        return view('guest.index', compact('resources'));
    }

    public function resourceDetail($id) {
        $resource = Resource::findOrFail($id);
        return view('guest.resource_detail', compact('resource'));
    }

    public function showRegisterForm() {
        return view('auth.register');
    }

    // --- PARTIE UTILISATEUR ---

    public function userDashboard() {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) { return redirect()->route('login'); }

        $myReservations = $user->reservations()->latest()->get();
        return view('user.dashboard', compact('myReservations'));
    }

    // --- PARTIE RESPONSABLE (Mise à jour) ---

    public function managerDashboard() {
        $managerId = Auth::id();

        // 1. Récupérer les ressources gérées par ce responsable
        $managedResources = Resource::where('manager_id', $managerId)->get();

        // 2. Récupérer les ID de ces ressources pour trouver les réservations liées
        $resourceIds = $managedResources->pluck('id');

        // 3. Récupérer les demandes en attente (avec les infos utilisateur et ressource)
        $pendingReservations = Reservation::whereIn('resource_id', $resourceIds)
            ->where('status', 'pending')
            ->with(['user', 'resource'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        return view('manager.dashboard', compact('managedResources', 'pendingReservations'));
    }

    // --- PARTIE ADMINISTRATEUR ---

    // Dashboard Administrateur
    public function adminDashboard() {
        // Statistiques
        $stats = [
            'users_count' => User::count(),
            'resources_count' => Resource::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
        ];

        // Utilisateurs en attente de validation (inactifs)
        $pendingUsers = User::where('is_active', false)->get();

        // Tous les utilisateurs (pour la gestion globale)
        $allUsers = User::where('role', '!=', 'admin')->get();

        // Liste des managers pour l'ajout de ressource
        $managers = User::where('role', 'manager')->orWhere('role', 'admin')->get();

        return view('admin.dashboard', compact('stats', 'pendingUsers', 'allUsers', 'managers'));
    }
}