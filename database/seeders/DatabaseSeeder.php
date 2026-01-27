<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Resource;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création de l'Administrateur
        User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@datacenter.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // 2. Création d'un Responsable Technique
        $manager = User::create([
            'name' => 'Jean Responsable',
            'email' => 'manager@datacenter.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'is_active' => true,
        ]);

        // 3. Création d'un Utilisateur Standard (NOUVEAU)
        User::create([
            'name' => 'Alice Utilisatrice',
            'email' => 'user@datacenter.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true, // Compte déjà validé pour tester tout de suite
        ]);

        // 4. Création de Ressources de test
        Resource::create([
            'label' => 'Serveur Dell PowerEdge R740',
            'category' => 'Serveur Physique',
            'description' => 'Serveur haute performance pour calcul intensif.',
            'location' => 'Baie A - Rack 4',
            'status' => 'available',
            'specifications' => ['CPU' => 'Intel Xeon Gold', 'RAM' => '64GB', 'Disk' => '2TB SSD'], // Laravel convertira ça en JSON grâce au 'casts'
            'manager_id' => $manager->id,
        ]);

        Resource::create([
            'label' => 'VM Ubuntu 22.04',
            'category' => 'Machine Virtuelle',
            'description' => 'Instance virtuelle pour hébergement web.',
            'location' => 'Cluster Virtualisation',
            'status' => 'available',
            'specifications' => ['vCPU' => '4', 'RAM' => '8GB', 'OS' => 'Ubuntu'],
            'manager_id' => $manager->id,
        ]);
        
        Resource::create([
            'label' => 'Switch Cisco Catalyst',
            'category' => 'Réseau',
            'description' => 'Switch 48 ports pour le sous-réseau Recherche.',
            'location' => 'Salle Réseau B',
            'status' => 'available',
            'specifications' => ['Ports' => '48x1Gbps', 'Uplink' => '10Gbps'],
            'manager_id' => $manager->id,
        ]);
    }
}