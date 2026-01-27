@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 40px auto;">
    
    <div class="card" style="padding: 30px; border-radius: 16px; border: 1px solid var(--border); background: var(--bg-surface);">
        <h2 style="color: var(--primary); margin-bottom: 10px;">📅 Nouvelle Réservation</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 25px;">
            Confirmez les dates pour bloquer votre ressource.
        </p>

        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Ressource sélectionnée</label>
                
                @if(isset($selectedResource))
                    <div style="background: var(--bg-background); padding: 12px; border: 1px solid var(--border); border-radius: 8px; color: var(--text-primary); font-weight: bold; display: flex; align-items: center; justify-content: space-between;">
                        <span>📦 {{ $selectedResource->label }} <span style="font-weight: normal; font-size: 0.8rem; color: var(--text-muted);">({{ $selectedResource->category }})</span></span>
                        <span style="color: #2ecc71;">● Disponible</span>
                    </div>
                    <input type="hidden" name="resource_id" value="{{ $selectedResource->id }}">
                @else
                    <select name="resource_id" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background); color: var(--text-primary);">
                        <option value="">-- Choisir une ressource --</option>
                        @foreach($resources as $resource)
                            <option value="{{ $resource->id }}">
                                {{ $resource->category }} : {{ $resource->label }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Date de début</label>
                    <input type="datetime-local" name="start_date" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background); color: var(--text-primary);">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Date de fin</label>
                    <input type="datetime-local" name="end_date" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background); color: var(--text-primary);">
                </div>
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Motif de la réservation <span style="font-weight: normal; color: var(--text-muted);">(Optionnel)</span></label>
                <textarea name="justification" rows="3" placeholder="Pour quel projet ?" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background); color: var(--text-primary);"></textarea>
            </div>

            <button type="submit" style="
                background: #088f8f; 
                color: white; 
                width: 100%; 
                padding: 14px; 
                font-weight: 700; 
                font-size: 1rem; 
                border: none; 
                border-radius: 8px; 
                cursor: pointer; 
                transition: background 0.3s;">
                Confirmer la réservation
            </button>
        </form>
    </div>
    
    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('user.dashboard') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem;">Annuler et retour</a>
    </div>
</div>
@endsection