<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    // Champs autorisés (doit correspondre à votre migration)
    protected $fillable = [
        'label',
        'category',
        'description',
        'specifications', // Stocké en JSON
        'location',
        'status',
        'manager_id',
    ];

    // RELATION 1 : Une ressource est gérée par un Responsable (User)
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // RELATION 2 : Une ressource peut avoir plusieurs réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}