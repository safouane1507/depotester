<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('label'); // Nom (ex: Serveur-01)
            $table->string('category'); // Serveur, VM, Stockage, Réseau
            
            // Caractéristiques techniques (CPU, RAM, OS, etc.)
            $table->text('description')->nullable();
            $table->json('specifications')->nullable(); 
            
            $table->string('location')->nullable(); // Emplacement dans le Data Center
            $table->enum('status', ['available', 'maintenance', 'occupied', 'inactive'])->default('available');
            
            // Lien vers le Responsable Technique (un utilisateur avec le rôle 'manager')
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};