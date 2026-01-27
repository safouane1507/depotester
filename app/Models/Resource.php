<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'category',
        'description',
        'specifications',
        'location',
        'status',
        'manager_id',
    ];

    // MODIFICATION IMPORTANTE : Convertit automatiquement le JSON en tableau PHP
    protected $casts = [
        'specifications' => 'array',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}