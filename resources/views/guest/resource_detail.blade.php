@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <a href="{{ route('guest.index') }}" style="display: inline-block; margin-bottom: 20px; color: var(--primary);">&larr; Retour</a>
    
    <div class="card">
        <h1>{{ $resource->label }}</h1>
        <span class="badge">{{ $resource->category }}</span>
        
        <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
            <h3>Caractéristiques Techniques</h3>
            <p><strong>Description :</strong> {{ $resource->description }}</p>
            <p><strong>Emplacement :</strong> {{ $resource->location }}</p>
            
            @if($resource->specifications)
                <div style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-top: 10px;">
                    <pre>{{ json_encode(json_decode($resource->specifications), JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif
        </div>

        <div style="margin-top: 30px; text-align: center;">
            @auth
                <a href="#" style="background: var(--success); color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Réserver cette ressource</a>
            @else
                <a href="{{ route('login') }}" style="background: var(--primary); color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Connectez-vous pour réserver</a>
            @endauth
        </div>
    </div>
</div>
@endsection