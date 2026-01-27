<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class ResourceManagerController extends Controller
{
    public function create()
    {
        return view('manager.resources.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'category' => 'required|string',
            'location' => 'required|string',
            'description' => 'nullable|string',
        ]);

        Resource::create([
            'label' => $request->label,
            'category' => $request->category,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'available',
            'manager_id' => Auth::id(),
        ]);

        return redirect()->route('manager.dashboard')->with('success', 'Équipement ajouté au catalogue.');
    }

    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);
        if ($resource->manager_id !== Auth::id()) {
            abort(403);
        }
        $resource->delete();
        return back()->with('success', 'Ressource supprimée.');
    }

    // Approuver la demande
    public function approveCustom($id) {
        $request = DB::table('custom_requests')->where('id', $id)->first();
    
        DB::table('custom_requests')->where('id', $id)->update(['status' => 'approved']);

        // Notification à l'utilisateur
        Notification::create([
            'user_id' => $request->user_id,
            'message' => "✅ Votre demande de configuration sur mesure ({$request->type}) a été approuvée !",
            'link' => route('user.dashboard'),
        ]);

        return back()->with('success', 'La demande a été approuvée.');
    }

    // Rejeter la demande
    public function rejectCustom($id) {
        $request = DB::table('custom_requests')->where('id', $id)->first();

        DB::table('custom_requests')->where('id', $id)->update(['status' => 'rejected']);

        Notification::create([
            'user_id' => $request->user_id,
            'message' => "❌ Votre demande de configuration pour {$request->type} a été refusée.",
            'link' => route('user.dashboard'),
        ]);

        return back()->with('success', 'La demande a été rejetée.');
    }

    // Afficher le formulaire d'édition
    public function edit($id)
    {
        $resource = Resource::findOrFail($id);
        
        // Sécurité : On vérifie si c'est le manager propriétaire OU un admin
        if ($resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, "Vous n'avez pas la permission de modifier cette ressource.");
        }

        return view('manager.resources.edit', compact('resource'));
    }

    // Enregistrer les modifications
    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        if ($resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:available,maintenance,inactive,occupied',
            'description' => 'nullable|string',
        ]);

        $resource->update([
            'status' => $request->status,
            'description' => $request->description,
        ]);

        // Redirection intelligente selon le rôle
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Ressource mise à jour.');
        }
        return redirect()->route('manager.dashboard')->with('success', 'Ressource mise à jour.');
    }

}