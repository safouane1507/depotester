<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Resource;

class AdminController extends Controller
{
    // --- GESTION UTILISATEURS ---

    // Activer un compte utilisateur
    public function activateUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => true, 'role' => 'user']); // On lui donne le rôle 'user' par défaut
        
        return back()->with('success', "Le compte de {$user->name} a été activé.");
    }

    // Désactiver/Bannir un utilisateur
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        // On inverse l'état (actif <-> inactif)
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Le compte a été $status.");
    }

    // --- GESTION RESSOURCES ---

    // Enregistrer une nouvelle ressource
    public function storeResource(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'category' => 'required|string',
            'manager_id' => 'required|exists:users,id',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Resource::create([
            'label' => $request->label,
            'category' => $request->category,
            'description' => $request->description,
            'location' => $request->location,
            'manager_id' => $request->manager_id,
            'status' => 'available',
            'specifications' => json_encode([]), // Vide pour l'instant
        ]);

        return back()->with('success', 'Nouvelle ressource ajoutée au parc.');
    }
}