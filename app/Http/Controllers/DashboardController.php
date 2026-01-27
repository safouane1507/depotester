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
    // --- PARTIE PUBLIQUE ---
    public function guestIndex(Request $request) {
        $resources = collect();
        if ($request->has('cat')) {
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
        $myCustomRequests = DB::table('custom_requests')->where('user_id', $user->id)->latest()->get();

        return view('user.dashboard', compact('myReservations', 'myCustomRequests'));
    }

    // --- PARTIE MANAGER ---
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

    // --- PARTIE ADMIN (CORRIGÉE ET RESTAURÉE) ---
    public function adminDashboard() {
        // 1. RESTAURATION DES STATISTIQUES
        $stats = [
            'users_count' => User::count(),
            'resources_count' => Resource::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
        ];

        // 2. Gestion des utilisateurs (Tout le monde sauf soi-même)
        $allUsers = User::where('id', '!=', Auth::id())->get();
        
        // 3. Liste des managers (pour l'ajout de ressource)
        $managers = User::where('role', 'manager')->orWhere('role', 'admin')->get();
        
        // 4. RESTAURATION DES RÉSERVATIONS EN ATTENTE
        $pendingReservations = Reservation::where('status', 'pending')
            ->with(['user', 'resource'])
            ->orderBy('created_at', 'asc')
            ->get();

        // 5. Demandes sur mesure
        $customRequests = DB::table('custom_requests')
            ->join('users', 'custom_requests.user_id', '=', 'users.id')
            ->select('custom_requests.*', 'users.name', 'users.email')
            ->where('custom_requests.status', 'pending')
            ->get();

        return view('admin.dashboard', compact('stats', 'allUsers', 'managers', 'pendingReservations', 'customRequests'));
    }

    // --- GESTION RESSOURCES (Admin & Manager) ---
    public function editResource($id) {
        $resource = Resource::findOrFail($id);
        if ($resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') { abort(403); }
        return view('manager.resources.edit', compact('resource'));
    }

    public function updateResource(Request $request, $id) {
        $resource = Resource::findOrFail($id);
        if ($resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') { abort(403); }

        $request->validate([ 'status' => 'required', 'description' => 'nullable' ]);
        $resource->update([ 'status' => $request->status, 'description' => $request->description ]);

        $route = Auth::user()->role === 'admin' ? 'admin.dashboard' : 'manager.dashboard';
        return redirect()->route($route)->with('success', 'Ressource mise à jour.');
    }
}