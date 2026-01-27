@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding-bottom: 50px;">
    <h1 style="border-bottom: 2px solid var(--primary); padding-bottom: 10px; margin-bottom: 30px;">Panneau d'Administration</h1>

    @if(session('success'))
        <div style="background: #e8f5e9; color: #2e7d32; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div class="card" style="text-align: center; padding: 20px; background: var(--bg-surface);">
            <h3 style="margin: 0; font-size: 2rem; color: var(--primary);">{{ $stats['users_count'] }}</h3>
            <span style="color: var(--text-muted);">Utilisateurs</span>
        </div>
        <div class="card" style="text-align: center; padding: 20px; background: var(--bg-surface);">
            <h3 style="margin: 0; font-size: 2rem; color: var(--primary);">{{ $stats['resources_count'] }}</h3>
            <span style="color: var(--text-muted);">Ressources</span>
        </div>
        <div class="card" style="text-align: center; padding: 20px; background: var(--bg-surface);">
            <h3 style="margin: 0; font-size: 2rem; color: orange;">{{ $stats['pending_reservations'] }}</h3>
            <span style="color: var(--text-muted);">Réservations en attente</span>
        </div>
    </div>

    <div class="card" style="margin-bottom: 30px; border-left: 5px solid var(--primary);">
        <h2 style="color: var(--primary);">✨ Demandes sur Mesure ({{ $customRequests->count() }})</h2>
        @if($customRequests->isEmpty())
            <p style="color: #777;">Aucune demande spéciale en attente.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="background: var(--bg-background); text-align: left;">
                        <th style="padding: 10px;">Utilisateur</th>
                        <th style="padding: 10px;">Besoin</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customRequests as $req)
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 10px;">{{ $req->name }}<br><small>{{ $req->email }}</small></td>
                            <td style="padding: 10px;">{{ $req->type }} <br> <small>{{ $req->cpu }} / {{ $req->ram }}</small></td>
                            <td style="padding: 10px;">
                                <div style="display: flex; gap: 5px;">
                                    <form action="{{ route('manager.custom.approve', $req->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" style="background: var(--success); color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">✔</button>
                                    </form>
                                    <form action="{{ route('manager.custom.reject', $req->id) }}" method="POST" onsubmit="return confirm('Rejeter ?');">
                                        @csrf
                                        <button type="submit" style="background: var(--danger); color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">✘</button>
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
        <h2>👥 Gestion des Utilisateurs</h2>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background: var(--bg-background); text-align: left;">
                    <th style="padding: 12px;">Nom</th>
                    <th style="padding: 12px;">Email</th>
                    <th style="padding: 12px;">Rôle</th>
                    <th style="padding: 12px;">Statut</th>
                    <th style="padding: 12px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allUsers as $user)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 12px;">{{ $user->name }}</td>
                        <td style="padding: 12px;">{{ $user->email }}</td>
                        <td style="padding: 12px;">
                            <span style="padding: 3px 8px; border-radius: 4px; background: #eee; font-size: 0.8rem;">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td style="padding: 12px;">
                            @if($user->is_active)
                                <span style="color: green; font-weight: bold;">Actif</span>
                            @else
                                <span style="color: red; font-weight: bold;">Inactif</span>
                            @endif
                        </td>
                        <td style="padding: 12px;">
                            <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="
                                    background: {{ $user->is_active ? '#e74c3c' : '#2ecc71' }}; 
                                    color: white; 
                                    border: none; 
                                    padding: 6px 12px; 
                                    border-radius: 6px; 
                                    cursor: pointer;
                                    font-weight: 600;">
                                    {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card" style="margin-top: 30px;">
        <h2>➕ Ajouter un Pack (Admin)</h2>
        <form action="{{ route('admin.resources.store') }}" method="POST" style="display: grid; gap: 15px; grid-template-columns: 1fr 1fr; margin-top: 20px;">
            @csrf
            <input type="text" name="label" placeholder="Nom du pack (ex: Serveur Gold)" required style="padding: 10px; border: 1px solid var(--border); border-radius: 6px;">
            
            <select name="category" required style="padding: 10px; border: 1px solid var(--border); border-radius: 6px;">
                <option value="Serveur Physique">Serveur Physique</option>
                <option value="Machine Virtuelle">Machine Virtuelle</option>
                <option value="Stockage">Stockage</option>
                <option value="Réseau">Réseau</option>
            </select>
            
            <input type="text" name="location" placeholder="Localisation" required style="padding: 10px; border: 1px solid var(--border); border-radius: 6px;">
            <input type="text" name="description" placeholder="Description courte" style="padding: 10px; border: 1px solid var(--border); border-radius: 6px;">
            
            <button type="submit" style="grid-column: span 2; background: var(--primary); color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; font-weight: bold;">Ajouter au catalogue</button>
        </form>
    </div>
</div>
@endsection