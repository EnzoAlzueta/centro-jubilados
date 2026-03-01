<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuota extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'socio_id',
        'mes',
        'anio',
        'monto',
        'pagado',
        'fecha_pago'
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class);
    }

    /**
     * Obtener el movimiento registrado para esta cuota.
     */
    public function movimiento()
    {
        return $this->morphOne(Movimiento::class , 'referencia');
    }
}