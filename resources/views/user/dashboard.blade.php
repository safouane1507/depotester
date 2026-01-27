@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    
    @if(session('success'))
        <div style="background: #e6f7f7; color: var(--primary); padding: 15px; border-radius: 8px; border: 1px solid var(--secondary); margin-bottom: 20px; text-align: center; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>Mon Espace Utilisateur</h1>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('reservations.create') }}" class="btn" style="text-decoration: none; padding: 10px 20px; background-color: #00796b; color: white;">
                + Nouvelle Réservation
            </a>
            <a href="{{ route('user.custom.create') }}" class="btn btn-primary" style="text-decoration: none; padding: 10px 20px;">
                ✨ Config. sur mesure
            </a>
        </div>
    </div>

    <div class="card" style="margin-bottom: 25px; border-left: 5px solid var(--primary);">
        <h3>✨ Mes Demandes Personnalisées</h3>
        @if($myCustomRequests->isEmpty())
            <p style="color: #777; font-style: italic; font-size: 0.9rem;">Vous n'avez pas encore fait de demande sur mesure.</p>
        @else
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; font-size: 0.85rem; color: var(--text-muted);">
                        <th style="padding: 10px;">Type</th>
                        <th style="padding: 10px;">Config</th>
                        <th style="padding: 10px;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myCustomRequests as $custom)
                    <tr style="border-top: 1px solid var(--border);">
                        <td style="padding: 10px; font-weight: 600;">{{ $custom->type }}</td>
                        <td style="padding: 10px; font-size: 0.85rem;">CPU: {{ $custom->cpu }} | RAM: {{ $custom->ram }}</td>
                        <td style="padding: 10px;">
                            <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; 
                                background: {{ $custom->status == 'pending' ? '#fff3e0' : ($custom->status == 'approved' ? '#e8f5e9' : '#ffebee') }}; 
                                color: {{ $custom->status == 'pending' ? '#ef6c00' : ($custom->status == 'approved' ? '#2e7d32' : '#c62828') }};">
                                {{ $custom->status == 'pending' ? 'En attente' : ($custom->status == 'approved' ? 'Approuvé' : 'Refusé') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="card">
        <h3>Mes Réservations</h3>
        @if($myReservations->isEmpty())
            <p style="color: #777; font-style: italic; text-align: center; padding: 20px;">Aucune réservation trouvée.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="background: #f4f4f4; text-align: left;">
                        <th style="padding: 10px;">Ressource</th>
                        <th style="padding: 10px;">Dates</th>
                        <th style="padding: 10px;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($myReservations as $reservation)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 12px; font-weight: bold; color: var(--primary);">{{ $reservation->resource->label }}</td>
                            <td style="padding: 12px; font-size: 0.85rem;">Du {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}<br>Au {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}</td>
                            <td style="padding: 12px;">
                                <span style="font-weight: bold; color: {{ $reservation->status == 'approved' ? 'green' : ($reservation->status == 'pending' ? 'orange' : 'red') }}">
                                    {{ $reservation->status == 'pending' ? 'En attente' : ($reservation->status == 'approved' ? 'Approuvée' : 'Refusée') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection