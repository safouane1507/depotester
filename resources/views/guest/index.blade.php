@extends('layouts.app')

@section('content')
    <section>
        <h1>Ressources du Data Center</h1>
        <p>Consultez en temps réel la disponibilité de nos équipements (Serveurs, Stockage, Réseau).</p>
        
        <div class="grid">
            @forelse($resources as $resource)
                <div class="card">
                    <span class="badge">{{ $resource->category }}</span>
                    <h3>{{ $resource->label }}</h3>
                    <p>{{ $resource->description }}</p>
                    
                    <ul style="list-style: none; padding: 0; font-size: 0.9rem;">
                        <li><strong>Localisation:</strong> {{ $resource->location }}</li>
                        <li><strong>État:</strong> <span class="status-available">Disponible</span></li>
                    </ul>
                    
                    <div style="margin-top: 15px; font-style: italic; font-size: 0.8rem; color: #666;">
                        Connectez-vous pour réserver cette ressource.
                    </div>
                </div>
            @empty
                <p>Aucune ressource disponible pour le moment.</p>
            @endforelse
        </div>
    </section>
@endsection