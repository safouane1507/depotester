@extends('layouts.app')

@section('content')
<div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: white;">
    <h2 style="text-align: center; color: #333;">Demande de Compte</h2>
    <p style="text-align: center; color: #666; font-size: 0.9em;">
        Veuillez remplir ce formulaire. Un administrateur validera votre compte sous 24h.
    </p>

    @if ($errors->any())
        <div style="background: #ffe6e6; color: #d63031; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/register-request') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Nom complet</label>
            <input type="text" name="name" value="{{ old('name') }}" required 
                   style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required 
                   style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Mot de passe</label>
            <input type="password" name="password" required 
                   style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px;">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" required 
                   style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <button type="submit" 
                style="width: 100%; padding: 10px; background: var(--primary); color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem;">
            Envoyer la demande
        </button>
    </form>

    <div style="text-align: center; margin-top: 15px;">
        <a href="{{ route('login') }}" style="color: #666; text-decoration: none;">Déjà un compte ? Se connecter</a>
    </div>
</div>
@endsection