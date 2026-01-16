<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\User;
class DashboardController extends Controller
{
    // Vue pour l'invité (Public)
    public function guestIndex() {
        $resources = Resource::where('status', 'available')->get();
        return view('guest.index', compact('resources'));
    }

    // Dashboard Utilisateur Interne
    public function userDashboard() {
        $myReservations = auth()->user()->reservations()->latest()->get();
        return view('user.dashboard', compact('myReservations'));
    }

    // Dashboard Responsable Technique
    public function managerDashboard() {
        // Ressources gérées par ce responsable
        $managedResources = Resource::where('manager_id', auth()->id())->get();
        return view('manager.dashboard', compact('managedResources'));
    }

    // Dashboard Administrateur
    public function adminDashboard() {
        $stats = [
            'users_count' => User::count(),
            'resources_count' => Resource::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }
}