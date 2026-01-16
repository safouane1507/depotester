@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <h1>Administration du Data Center</h1>

    <div class="grid" style="margin-bottom: 40px; grid-template-columns: repeat(3, 1fr);">
        <div class="card" style="text-align: center; border-top: 4px solid var(--accent);">
            <div style="font-size: 2rem; font-weight: bold;">{{ $stats['users_count'] }}</div>
            <div>Utilisateurs Inscrits</div>
        </div>
        <div class="card" style="text-align: center; border-top: 4px solid var(--success);">
            <div style="font-size: 2rem; font-weight: bold;">{{ $stats['resources_count'] }}</div>
            <div>Ressources Totales</div>
        </div>
        <div class="card" style="text-align: center; border-top: 4px solid orange;">
            <div style="font-size: 2rem; font-weight: bold;">{{ $stats['pending_reservations'] }}</div>
            <div>Réservations en attente</div>
        </div>
    </div>

    @if($pendingUsers->isNotEmpty())
        <div class="card" style="margin-bottom: 30px; border-left: 5px solid red;">
            <h2 style="color: #c0392b;">👤 Comptes en attente de validation</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f9f9f9;">
                        <th style="padding: 10px; text-align: left;">Nom</th>
                        <th style="padding: 10px; text-align: left;">Email</th>
                        <th style="padding: 10px; text-align: left;">Date demande</th>
                        <th style="padding: 10px; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;">{{ $user->name }}</td>
                            <td style="padding: 10px;">{{ $user->email }}</td>
                            <td style="padding: 10px;">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td style="padding: 10px; text-align: right;">
                                <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background: var(--success); color: white; border: none; padding: 5px 15px; border-radius: 4px; cursor: pointer;">
                                        Activer le compte
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="card">
        <h2>➕ Ajouter une Ressource</h2>
        <form action="{{ route('admin.resources.store') }}" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            @csrf
            <div>
                <label>Nom de la ressource</label>
                <input type="text" name="label" required style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
            <div>
                <label>Catégorie</label>
                <select name="category" required style="width: 100%; padding: 8px; margin-top: 5px;">
                    <option value="Serveur">Serveur Physique</option>
                    <option value="VM">Machine Virtuelle</option>
                    <option value="Stockage">Baie de Stockage</option>
                    <option value="Réseau">Switch / Routeur</option>
                </select>
            </div>
            <div>
                <label>Responsable Technique</label>
                <select name="manager_id" required style="width: 100%; padding: 8px; margin-top: 5px;">
                    @foreach($managers as $manager)
                        <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->role }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>Emplacement</label>
                <input type="text" name="location" placeholder="Ex: Baie A2" style="width: 100%; padding: 8px; margin-top: 5px;">
            </div>
            <div style="grid-column: span 2;">
                <label>Description</label>
                <textarea name="description" rows="2" style="width: 100%; padding: 8px; margin-top: 5px;"></textarea>
            </div>
            <div style="grid-column: span 2;">
                <button type="submit" style="background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">
                    Enregistrer la ressource
                </button>
            </div>
        </form>
    </div>
</div>
@endsection