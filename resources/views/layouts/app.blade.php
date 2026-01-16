<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataCenter - Gestion des Ressources</title>
    <style>
        /* CSS PERSONNALISÉ (VANILLA) */
        :root {
            --primary: #2c3e50;
            --accent: #3498db;
            --bg: #f4f7f6;
            --white: #ffffff;
            --text: #333;
            --success: #27ae60;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            line-height: 1.6;
        }

        header {
            background: var(--primary);
            color: var(--white);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .logo { font-size: 1.5rem; font-weight: bold; }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 15px;
            border-radius: 4px;
            transition: 0.3s;
        }

        nav a.btn-register { background: var(--accent); }
        nav a:hover { opacity: 0.8; }

        .container { padding: 40px 5%; }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .card {
            background: var(--white);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-top: 5px solid var(--accent);
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            background: #e0e0e0;
        }

        .status-available { color: var(--success); font-weight: bold; }

        footer { text-align: center; padding: 20px; color: #777; font-size: 0.9rem; }
    </style>
</head>
<body>

<header>
    <div class="logo">DC-Manager</div>
    <nav>
        <a href="/">Ressources</a>
        <a href="/login">Connexion</a>
        <a href="/register-request" class="btn-register">Demander un compte</a>
    </nav>
</header>

<main class="container">
    @yield('content')
</main>

<footer>
    &copy; 2026 Data Center - Gestion Interne
</footer>

</body>
</html>