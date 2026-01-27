<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\Resource;
use App\Models\Notification;
use App\Models\User;

class ReservationController extends Controller
{
    // --- ESPACE UTILISATEUR ---

    /**
     * Affiche le formulaire de réservation standard
     */
    public function create(Request $request)
    {
        // On récupère l'ID s'il est passé dans l'URL
        $selectedResource = null;
        if ($request->has('resource_id')) {
            $selectedResource = Resource::find($request->resource_id);
        }

        // Propose uniquement les ressources marquées comme 'available'
        $resources = Resource::where('status', 'available')->get();
        return view('user.reservations.create', compact('resources', 'selectedResource'));
    }

    /**
     * Enregistre une demande de réservation standard (avec gestion des conflits)
     */
    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'justification' => 'required|string|max:500',
        ]);

        // Vérification des conflits (Overlapping)
        $conflit = Reservation::where('resource_id', $request->resource_id)
            ->whereIn('status', ['approved', 'active'])
            ->where(function ($query) use ($request) {
                $query->where('start_date', '<', $request->end_date)
                      ->where('end_date', '>', $request->start_date);
            })
            ->exists();

        if ($conflit) {
            return back()
                ->withInput()
                ->withErrors(['resource_id' => 'Cette ressource est déjà réservée sur ce créneau horaire.']);
        }

        Reservation::create([
            'user_id' => Auth::id(),
            'resource_id' => $request->resource_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'justification' => $request->justification,
            'status' => 'pending',
        ]);

        // Notification au Manager
        $resource = Resource::find($request->resource_id);
        if ($resource && $resource->manager_id) {
            Notification::create([
                'user_id' => $resource->manager_id,
                'message' => "Nouvelle demande de " . Auth::user()->name . " pour " . $resource->label,
                'link' => route('manager.dashboard'),
            ]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Votre demande est enregistrée et en attente de validation.');
    }

    // --- CONFIGURATION SUR MESURE (CUSTOM REQUESTS) ---

    /**
     * Affiche le formulaire de configuration personnalisée
     * Résout l'erreur : Call to undefined method createCustom()
     */
    public function createCustom()
    {
        return view('user.custom_create');
    }

    /**
     * Enregistre la demande personnalisée dans la table custom_requests
     */
    public function storeCustom(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'cpu' => 'required|string',
            'ram' => 'required|string',
            'storage' => 'required|string',
            'justification' => 'required|string|max:1000',
        ]);

        // Insertion en base de données
        DB::table('custom_requests')->insert([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'cpu' => $request->cpu,
            'ram' => $request->ram,
            'storage' => $request->storage,
            'justification' => $request->justification,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Notification à l'Admin (Optionnel)
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'message' => Auth::user()->name . " a demandé une configuration sur mesure.",
                'link' => route('admin.dashboard'),
            ]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Votre demande de configuration personnalisée a été envoyée à l\'administrateur.');
    }

    // --- ESPACE RESPONSABLE (MANAGER) ---

    /**
     * Traite (Approuve ou Refuse) une réservation standard
     */
    public function handleRequest(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Sécurité : Seul le manager de la ressource peut décider
       if ($reservation->resource->manager_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, "Action non autorisée sur cette ressource.");
        }

        $messageUser = "";

        if ($request->action === 'approve') {
            $reservation->update(['status' => 'approved']);
            $messageUser = "✅ Votre réservation pour " . $reservation->resource->label . " a été acceptée !";
            
        } elseif ($request->action === 'reject') {
            $reservation->update([
                'status' => 'rejected',
                'manager_feedback' => 'Demande refusée par le responsable.'
            ]);
            $messageUser = "❌ Votre réservation pour " . $reservation->resource->label . " a été refusée.";
        }

        // Notification à l'utilisateur
        if ($messageUser) {
            Notification::create([
                'user_id' => $reservation->user_id,
                'message' => $messageUser,
                'link' => route('user.dashboard'),
            ]);
        }

        return back()->with('success', 'La demande a été traitée.');
    }
}