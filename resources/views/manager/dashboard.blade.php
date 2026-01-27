@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding-bottom: 50px;">
    <h1 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px; margin-bottom: 30px;">Espace Responsable Technique</h1>

    @if(session('success'))
        <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c8e6c9;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid var(--primary);">
        <h2 style="color: var(--primary);">✨ Nouvelles demandes sur mesure ({{ $customRequests->count() }})</h2>
        
        @if($customRequests->isEmpty())
            <p style="color: #777; font-style: italic;">Aucune demande de configuration personnalisée.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead style="background: var(--bg-background);">
                    <tr>
                        <th style="padding: 12px; text-align: left;">Utilisateur</th>
                        <th style="padding: 12px; text-align: left;">Config. Demandée</th>
                        <th style="padding: 12px; text-align: left;">Justification</th>
                        <th style="padding: 12px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customRequests as $req)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px;">
                                <strong>{{ $req->name }}</strong><br>
                                <small>{{ $req->email }}</small>
                            </td>
                            <td style="padding: 12px; font-size: 0.85rem;">
                                <b>Type:</b> {{ $req->type }}<br>
                                <b>CPU:</b> {{ $req->cpu }} | <b>RAM:</b> {{ $req->ram }}<br>
                                <b>Disk:</b> {{ $req->storage }}
                            </td>
                            <td style="padding: 12px; font-style: italic; font-size: 0.85rem;">"{{ Str::limit($req->justification, 30) }}"</td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <form action="{{ route('manager.custom.approve', $req->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="background: #2ecc71; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">✔ Accepter</button>
                                    </form>

                                    <form action="{{ route('manager.custom.reject', $req->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment rejeter cette demande ?');">
                                        @csrf
                                        <button type="submit" style="background: #e74c3c; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">✘ Rejeter</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid orange;">
        <h2 style="color: #d35400;">🔔 Réservations en attente ({{ $pendingReservations->count() }})</h2>
        @if($pendingReservations->isEmpty())
            <p style="color: #777; font-style: italic;">Aucune réservation à traiter.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead style="background: var(--bg-background);">
                    <tr>
                        <th style="padding: 12px; text-align: left;">Utilisateur</th>
                        <th style="padding: 12px; text-align: left;">Ressource</th>
                        <th style="padding: 12px; text-align: left;">Dates</th>
                        <th style="padding: 12px; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingReservations as $reservation)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 12px;"><strong>{{ $reservation->user->name }}</strong></td>
                            <td style="padding: 12px; color: var(--primary);">{{ $reservation->resource->label }}</td>
                            <td style="padding: 12px; font-size: 0.85rem;">
                                Du {{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m H:i') }}<br>
                                au {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m H:i') }}
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <form action="{{ route('manager.reservations.handle', $reservation->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" style="background: #2ecc71; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">✔ Accepter</button>
                                    </form>

                                    <form action="{{ route('manager.reservations.handle', $reservation->id) }}" method="POST" onsubmit="return confirm('Refuser cette demande ?');">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" style="background: #e74c3c; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">✘ Refuser</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>🛠 Gestion du Catalogue ({{ $managedResources->count() }})</h2>
            <a href="{{ route('manager.resources.create') }}" style="background: var(--primary); color: white; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: bold;">+ Ajouter</a>
        </div>
        <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px;">
            @foreach($managedResources as $resource)
                <div class="card" style="padding: 15px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg-surface);">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-size: 0.7rem; font-weight: 800; color: var(--primary); text-transform: uppercase;">{{ $resource->category }}</span>
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $resource->status == 'available' ? '#00b894' : ($resource->status == 'maintenance' ? 'orange' : '#d63031') }};"></div>
                    </div>
                    <h3 style="font-size: 1.1rem; margin: 10px 0;">{{ $resource->label }}</h3>
                    
                    <div style="margin-top: 15px; display: flex; gap: 10px;">
                        <a href="{{ route('manager.resources.edit', $resource->id) }}" style="flex: 1; text-align: center; font-size: 0.8rem; padding: 6px; background: var(--bg-background); border: 1px solid var(--border); border-radius: 6px; text-decoration: none; color: var(--text-primary); font-weight: 600;">⚙ Gérer</a>
                        
                        <form action="{{ route('manager.resources.destroy', $resource->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Supprimer définitivement cette ressource ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="width: 100%; font-size: 0.8rem; padding: 6px; background: rgba(214, 48, 49, 0.1); color: #d63031; border: 1px solid rgba(214, 48, 49, 0.2); border-radius: 6px; cursor: pointer; font-weight: 600;">Supprimer</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection