@extends('layouts.app')

@section('content')
<div style="max-width: 400px; margin: 50px auto; background: var(--bg-surface); padding: 30px; border-radius: 12px; border: 1px solid var(--border); box-shadow: var(--shadow);">
    <h2 style="text-align: center; color: var(--primary); margin-bottom: 25px;">Connexion</h2>

    @if ($errors->any())
        <div style="color: #d63031; background: rgba(214, 48, 49, 0.1); padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 0.9em;">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Email</label>
            <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-background); color: var(--text-primary);">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:5px;">Mot de passe</label>
            <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 8px; background: var(--bg-background); color: var(--text-primary);">
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: bold;">
            Se connecter
        </button>
    </form>
</div>
@endsection
