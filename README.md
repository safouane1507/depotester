# 🖥️🌐 DataCenter Manager
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/javascript-%23F7DF1E.svg?style=for-the-badge&logo=javascript&logoColor=black)

**DataCenter Manager** est une application web robuste conçue pour **centraliser la gestion, la réservation et le suivi des ressources informatiques**  
(serveurs physiques, machines virtuelles, stockage et équipements réseau).

> 📍 Projet académique – **FST de Tanger**  
> 🎓 Filière : **IDAI – 2025/2026**  
> 🎯 Axes principaux : Sécurité, traçabilité et interface moderne **sans framework CSS externe**
---
# 🎥DEMO


https://github.com/user-attachments/assets/277b569e-59aa-4e78-b76e-1340aef7c5c3

---

## 👥 Équipe du Projet

- **Encadrants :**
  - Pr. **Y. El Yusufi**
  - Pr. **M. Ait Kbir**

- **Créateurs :**
  - **Safouane Bousakhra**
  - **Mohamed Reda Benmoussa**
  - **Ilyas Gourrou**
  - **Mohamed Abdallaoui Alaoui**

---

## 🚀 Fonctionnalités Clés

### 🔐 Gestion Multi-profils
- 4 rôles distincts :
  - **Invité**
  - **Utilisateur**
  - **Manager**
  - **Admin**
- Permissions strictes gérées via **Middleware Laravel**

### 📅 Algorithme Anti-Conflit
- Vérification intelligente des disponibilités
- Empêche les chevauchements de dates lors des réservations

### 🌗 Mode Sombre Dynamique
- Light / Dark Mode
- Implémentation **100% CSS natif**

### 🛠️ Suivi Technique
- Gestion des incidents
- Demandes de configurations personnalisées
- Tableaux de bord statistiques avec **Chart.js**

---

## 🛠️ Technologies Utilisées

| Couche        | Technologie |
|--------------|------------|
| Backend      | Laravel 11 (PHP) |
| Base de données | MySQL |
| Frontend     | Blade, CSS3 Natif |
| JavaScript   | Vanilla JS |
| Visualisation | Chart.js |

---

## 📂 Structure du Projet

```bash
DataCenter_Manager/
├── app/
│   ├── Http/
│   │   ├── Controllers/         # Logique métier (Admin, Auth, Reservation...)
│   │   └── Middleware/          # Sécurité & gestion des rôles
│   └── Models/                  # Modèles Eloquent (Resource, User, Incident...)
├── bootstrap/
├── config/                      # Configuration globale
├── database/
│   ├── migrations/              # Schémas des tables
│   └── seeders/                 # Données de test
├── public/
│   ├── css/
│   │   └── style.css            # Light & Dark Mode
│   ├── images/
│   │   └── fst.png              # Logos & favicons
│   └── js/
│       └── app.js               # Logique JavaScript
├── resources/
│   └── views/
│       ├── admin/               # Vues Administrateur
│       ├── auth/                # Login / Register
│       ├── layouts/             # Layout principal
│       ├── manager/             # Responsable technique
│       ├── user/                # Utilisateur interne
│       └── welcome.blade.php    # Page d'accueil
├── routes/
│   └── web.php                  # Définition des routes
└── storage/
```

---

## 🔧 Installation Rapide

### 1️⃣ Cloner le projet
```bash
git clone https://github.com/safouane1507/DataCenter_Manager.git
```

### 2️⃣ Installer les dépendances
```bash
composer install
npm install
```

### 3️⃣ Configurer l'environnement
```bash
cp .env.example .env
```
>Configurer les accès MySQL dans le fichier .env

### 4️⃣ Lancer les migrations & seeders
```bash
php artisan migrate --seed
```

### 5️⃣ Démarrer le serveur
```bash
php artisan serve
```
---
# 📊 Statistiques & Sécurité
* **Journalisation complète des actions utilisateurs**

> **Tableau de bord Administrateur :**

> Taux d’occupation global du Data Center

> Suivi des ressources critiques

> Accès strictement filtré :

> Les ressources sensibles restent sous la supervision des Managers désignés

## 📌 Remarques

-  Interface moderne sans Bootstrap ni Tailwind

- Architecture claire et maintenable

- Projet prêt pour une évolution vers un contexte professionnel réel
  
---

# 📜 Licence

**© Projet académique – Usage pédagogique uniquement.**
