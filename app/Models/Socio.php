<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relación: 1 Socio vive en 1 Barrio
    public function barrio()
    {
        return $this->belongsTo(Barrio::class);
    }

    // Relación: 1 Socio puede tener MUCHOS Alquileres
    public function alquileres()
    {
        return $this->hasMany(Alquiler::class);
    }

    public function cuotas()
    {
        return $this->hasMany(Cuota::class);
    }
}