@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <a href="{{ route('user.dashboard') }}" style="color: var(--primary); text-decoration: none;">&larr; Retour au tableau de bord</a>
    
    <div class="card" style="margin-top: 20px;">
        <h2 style="margin-top: 0;">Nouvelle Réservation</h2>
        <p>Veuillez remplir les détails ci-dessous pour soumettre votre demande.</p>

        @if ($errors->any())
            <div style="background: #ffe6e6; color: #d63031; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Ressource souhaitée</label>
                <select name="resource_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">-- Sélectionner une ressource --</option>
                    @foreach($resources as $resource)
                        <option value="{{ $resource->id }}">
                            {{ $resource->category }} : {{ $resource->label }} ({{ $resource->location }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Date de début</label>
                    <input type="datetime-local" name="start_date" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Date de fin</label>
                    <input type="datetime-local" name="end_date" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Motif de la demande</label>
                <textarea name="justification" rows="4" required placeholder="Ex: Besoin pour le projet de fin d'études..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
            </div>

            <button type="submit" style="background: var(--primary); color: white; width: 100%; padding: 14px; font-weight: 700; font-size: 1rem; border: none; border-radius: 8px; cursor: pointer;">
                Confirmer la demande
            </button>
        </form>
    </div>
</div>
@endsection