<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataCenter Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- VARIABLES DE COULEURS --- */
        :root {
            /* ☀️ Light Mode */
            --bg-background: #F6F9F9;
            --bg-surface: #FFFFFF;
            --primary: #0FA3A3;
            --secondary: #5FC9C4;
            --text-primary: #1C2F30;
            --text-muted: #647F80;
            --border: #DCECEC;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --radius: 12px;
        }

        /* 🌙 Dark Mode */
        body.dark {
            --bg-background: #081B1C;
            --bg-surface: #102C2D;
            --primary: #4DB6AC;
            --secondary: #80CBC4;
            --text-primary: #E0F2F1;
            --text-muted: #B2DFDB;
            --border: #294C4C;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-background);
            color: var(--text-primary);
            margin: 0;
            transition: all 0.3s ease;
        }

        header {
            background: var(--bg-surface);
            border-bottom: 1px solid var(--border);
            padding: 0 5%;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow);
        }

        .logo { 
            font-size: 1.5rem; 
            font-weight: 800; 
            color: var(--primary); 
            text-decoration: none;
        }
        .logo span { color: var(--text-primary); }

        nav { display: flex; align-items: center; gap: 15px; }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
            cursor: pointer;
        }
        .nav-link:hover { color: var(--primary); }

        /* --- MENU DÉROULANT --- */
        .dropdown-container { position: relative; height: 70px; display: flex; align-items: center; }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 65px;
            left: -20px;
            width: 240px;
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            padding: 10px;
            z-index: 1001;
            content: "";
        }

        .dropdown-menu::before {
            content: "";
            position: absolute;
            top: -15px; /* Comble le vide de 15px au-dessus */
            left: 0;
            width: 100%;
            height: 15px;
            background: transparent; /* Invisible mais détecte la souris */
        }

        .dropdown-container:hover .dropdown-menu { display: block; animation: fadeIn 0.2s ease-out; }
        
        .dropdown-item {
            display: flex; align-items: center; padding: 10px 15px;
            color: var(--text-muted); text-decoration: none; border-radius: 8px;
        }
        .dropdown-item:hover { background: var(--bg-background); color: var(--primary); }

        /* --- BOUTONS --- */
        .btn { padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; cursor: pointer; border: none; }
        .btn-outline { border: 1px solid var(--border); color: var(--text-primary); background: transparent; }
        .btn-primary { background: var(--primary); color: #fff; }

        /* --- SALUTATION --- */
        .user-greeting {
            display: flex; align-items: center; gap: 10px;
            padding: 5px 12px; border-radius: 30px;
            background: var(--bg-background); border: 1px solid var(--border);
        }
        .user-avatar {
            width: 32px; height: 32px; background: var(--secondary); color: white;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: bold;
        }

        /* --- TOGGLE SWITCH --- */
        #theme-toggle {
            background: var(--bg-background);
            border: 1px solid var(--border);
            color: var(--text-primary);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.3s;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="light"> <header>
    <a href="/" class="logo">Data<span>Center</span></a>

    <nav>
        <div class="dropdown-container">
            <a href="/" class="nav-link">Ressources ▾</a>
            <div class="dropdown-menu">
                <div style="padding: 0 15px 5px; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Filtrer par</div>
                <a href="/?cat=Serveur Physique" class="dropdown-item">🖥️ Serveurs</a>
                <a href="/?cat=Machine Virtuelle" class="dropdown-item">☁️ Virtuel (VM)</a>
                <a href="/?cat=Stockage" class="dropdown-item">💾 Stockage</a>
                <a href="/?cat=Réseau" class="dropdown-item">🌐 Réseau</a>
            </div>
        </div>

        <button id="theme-toggle" title="Changer de mode">
            <span id="theme-toggle-icon">🌙</span>
        </button>

        @guest
            <a href="{{ route('login') }}" class="btn btn-outline">Connexion</a>
            <a href="{{ route('register.request') }}" class="btn btn-primary">Inscription</a>
        @else
            @php
                $roleTitle = "M.";
                if(Auth::user()->role === 'manager') $roleTitle = "Ing.";
                if(Auth::user()->role === 'admin') $roleTitle = "Admin";
            @endphp
            
            <div class="user-greeting">
                <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div style="font-size: 0.85rem; line-height: 1.2;">
                    <span style="color: var(--text-muted); font-size: 0.8em;">Bonjour,</span><br>
                    <strong style="color: var(--text-primary);">{{ $roleTitle }} {{ Auth::user()->name }}</strong>
                </div>
            </div>

            <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : (Auth::user()->role === 'manager' ? route('manager.dashboard') : route('user.dashboard')) }}" class="nav-link">
                Dashboard
            </a>

            {{-- here --}}
            <a href="{{ route('profile.settings') }}" class="nav-link" style="margin-right: 15px;">⚙️ Paramètres</a> 
            {{-- here --}}

            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="nav-link" style="background:none; border:none; font-size:1.2rem;" title="Déconnexion">⏻</button>
            </form>
        @endguest
    </nav>
</header>

<main class="container">
    @yield('content')
</main>

<footer style="text-align: center; padding: 40px; color: var(--text-muted); border-top: 1px solid var(--border); margin-top: 60px;">
    &copy; 2026 DataCenter Management System.
</footer>

<script>
    const btn = document.getElementById('theme-toggle');
    const icon = document.getElementById('theme-toggle-icon');
    const body = document.body;

    // Charger le thème sauvegardé
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        body.classList.add('dark');
        icon.textContent = '☀️';
    }

    btn.addEventListener('click', () => {
        body.classList.toggle('dark');
        
        if (body.classList.contains('dark')) {
            localStorage.setItem('theme', 'dark');
            icon.textContent = '☀️';
        } else {
            localStorage.setItem('theme', 'light');
            icon.textContent = '🌙';
        }
    });
</script>
</body>
</html>