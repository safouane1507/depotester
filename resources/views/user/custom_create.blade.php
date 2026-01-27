@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 40px auto;">
    <div class="card" style="padding: 30px; border-radius: 16px; border: 1px solid var(--border); background: var(--bg-surface);">
        <h2 style="color: var(--primary); margin-bottom: 10px;">✨ Configuration sur Mesure</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 25px;">
            Décrivez précisément l'infrastructure dont vous avez besoin.
        </p>

        <form action="{{ route('user.custom.store') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Type d'équipement</label>
                <select name="type" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background); color: var(--text-primary);">
                    <option value="Serveur Physique">Serveur Physique Dédié</option>
                    <option value="VM Haute Performance">Machine Virtuelle (VM)</option>
                    <option value="Stockage Cloud">Baie de Stockage</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Processeur (CPU)</label>
                    <select name="cpu" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background);">
                        <option value="8 Cores">8 Cores</option>
                        <option value="16 Cores">16 Cores</option>
                        <option value="32 Cores">32 Cores</option>
                        <option value="64 Cores">64 Cores</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Mémoire (RAM)</label>
                    <select name="ram" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background);">
                        <option value="16 GB">16 GB</option>
                        <option value="32 GB">32 GB</option>
                        <option value="64 GB">64 GB</option>
                        <option value="128 GB">128 GB</option>
                        <option value="256 GB">256 GB</option>
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Espace Disque (Storage)</label>
                <select name="storage" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background);">
                    <option value="256 GB SSD">256 GB SSD</option>
                    <option value="512 GB SSD">512 GB SSD</option>
                    <option value="1 TB NVMe">1 TB NVMe</option>
                    <option value="2 TB NVMe">2 TB NVMe</option>
                    <option value="4 TB RAID">4 TB RAID</option>
                    <option value="8 TB RAID">8 TB RAID</option>
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 5px;">Justification technique</label>
                <textarea name="justification" rows="4" placeholder="Pourquoi avez-vous besoin de cette configuration ?" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); background: var(--bg-background); color: var(--text-primary);"></textarea>
            </div>

            <button type="submit" style="background: var(--primary); color: white; width: 100%; padding: 14px; font-weight: 700; font-size: 1rem; border: none; border-radius: 8px; cursor: pointer;">
                Envoyer ma demande
            </button>
        </form>
    </div>
</div>
@endsection