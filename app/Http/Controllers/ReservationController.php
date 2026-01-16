<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Resource;

class ReservationController extends Controller
{
    // --- Espace UTILISATEUR ---

    // 1. Afficher le formulaire de réservation
    public function create()
    {
        $resources = Resource::where('status', 'available')->get();
        return view('user.reservations.create', compact('resources'));
    }

    // 2. Enregistrer la demande
    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'justification' => 'required|string|max:500',
        ]);

        Reservation::create([
            'user_id' => Auth::id(),
            'resource_id' => $request->resource_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'justification' => $request->justification,
            'status' => 'pending',
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Votre demande est enregistrée et en attente de validation.');
    }

    // --- Espace RESPONSABLE ---

    // 3. Traiter (Accepter/Refuser) une demande
    public function handleRequest(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Sécurité : Vérifier que le manager connecté est bien responsable de cette ressource
        // Note: Cela suppose que la relation 'resource' est chargée ou accessible via $reservation->resource
        if ($reservation->resource->manager_id !== Auth::id()) {
            abort(403, "Action non autorisée sur cette ressource.");
        }

        if ($request->action === 'approve') {
            $reservation->update(['status' => 'approved']);
            // Optionnel : Mettre à jour le statut de la ressource si nécessaire
            // $reservation->resource->update(['status' => 'occupied']);
        } elseif ($request->action === 'reject') {
            $reservation->update([
                'status' => 'rejected',
                'manager_feedback' => 'Demande refusée par le responsable.'
            ]);
        }

        return back()->with('success', 'La demande a été traitée.');
    }
}