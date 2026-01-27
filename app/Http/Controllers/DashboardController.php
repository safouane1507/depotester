<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\User;

class DashboardController extends Controller
{
    // --- PARTIE PUBLIQUE AVEC FILTRAGE ---
    public function guestIndex(Request $request) {
    $resources = collect();

    if ($request->has('cat')) {
        // On retire la contrainte stricte sur le manager_id pour que les packs Admin s'affichent aussi
        $resources = Resource::where('status', 'available')
            ->where('category', $request->cat)
            ->get();
    }
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
        $user = Auth::user();
        if (!$user) { return redirect()->route('login'); }
        
        $myReservations = $user->reservations()->latest()->get();
        
        $myCustomRequests = DB::table('custom_requests')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('user.dashboard', compact('myReservations', 'myCustomRequests'));
    }

    // --- PARTIE RESPONSABLE ---
    public function managerDashboard() {
        $managerId = Auth::id();
        $managedResources = Resource::where('manager_id', $managerId)->get();
        $resourceIds = $managedResources->pluck('id');
        
        $pendingReservations = Reservation::whereIn('resource_id', $resourceIds)
            ->where('status', 'pending')
            ->with(['user', 'resource'])
            ->orderBy('created_at', 'asc')
            ->get();

        $customRequests = DB::table('custom_requests')
            ->join('users', 'custom_requests.user_id', '=', 'users.id')
            ->select('custom_requests.*', 'users.name', 'users.email')
            ->where('custom_requests.status', 'pending')
            ->get();
        
        return view('manager.dashboard', compact('managedResources', 'pendingReservations', 'customRequests'));
    }

    // --- PARTIE ADMINISTRATEUR ---
   public function adminDashboard() {
        $stats = [
            'users_count' => User::count(),
            'resources_count' => Resource::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
        ];

        // Utilisateurs en attente ou inactifs
        $pendingUsers = User::where('is_active', false)->get();
        // Tous les utilisateurs sauf admin
        $allUsers = User::where('role', '!=', 'admin')->get();
        // Liste des managers pour info
        $managers = User::where('role', 'manager')->orWhere('role', 'admin')->get();
        
        // AJOUT : Récupérer les demandes sur mesure pour l'admin
        $customRequests = DB::table('custom_requests')
            ->join('users', 'custom_requests.user_id', '=', 'users.id')
            ->select('custom_requests.*', 'users.name', 'users.email')
            ->where('custom_requests.status', 'pending')
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingUsers', 'allUsers', 'customRequests'));
    }

    // --- GESTION DES RESSOURCES ---
    public function editResource($id) {
        $resource = Resource::findOrFail($id);
        if ($resource->manager_id !== Auth::id()) { abort(403); }
        return view('manager.resources.edit', compact('resource'));
    }

    public function updateResource(Request $request, $id) {
        $resource = Resource::findOrFail($id);
        if ($resource->manager_id !== Auth::id()) { abort(403); }

        $request->validate([
            'status' => 'required|in:available,maintenance,inactive,occupied',
            'description' => 'nullable|string',
        ]);

        $resource->update([
            'status' => $request->status,
            'description' => $request->description,
        ]);

        return redirect()->route('manager.dashboard')->with('success', 'Ressource mise à jour avec succès.');
    }
}