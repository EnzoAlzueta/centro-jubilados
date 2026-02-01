<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alquiler extends Model
{
    use HasFactory;

    protected $table = 'alquileres';
    protected $guarded = [];

    // Relación: 1 Alquiler pertenece a 1 Sector
    public function sector(){
        return $this->belongsTo(Sector::class);
    }

    // Relación: 1 Alquiler lo pide 1 Socio (opcional)
    public function socio(){
        return $this->belongsTo(Socio::class);
    }

    // Relación: Muchos a Muchos con Utileria
    public function utileria(){
        return $this->belongsToMany(Utileria::class, 'alquiler_utileria')
                    ->withPivot('cantidad') // Sirve para saber cuantas 'sillas' se llevaron
                    ->withTimestamps();
    }
}
