<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $fillable = [
        'fecha',
        'tipo',
        'concepto',
        'monto',
        'categoria',
        'referencia_id',
        'referencia_type'
    ];

    /**
     * Obtener el modelo de referencia (Alquiler, Cuota, etc.)
     */
    public function referencia()
    {
        return $this->morphTo();
    }
}