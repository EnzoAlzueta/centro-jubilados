<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utileria extends Model
{
    use HasFactory;
    protected $table = 'utilerias';
    protected $guarded = [];

    /**
     * Relación: Muchos a Muchos con Alquileres
     */
    public function alquileres()
    {
        return $this->belongsToMany(Alquiler::class , 'alquiler_utileria')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    /**
     * Calcula el stock disponible restando las cantidades reservadas del stock total.
     * Solo considera alquileres activos (no cancelados).
     */
    public function getStockDisponible()
    {
        $cantidadReservada = $this->alquileres()
            ->whereIn('estado', ['reservado', 'confirmado', 'finalizado'])
            ->sum('alquiler_utileria.cantidad');

        return $this->stock_total - $cantidadReservada;
    }
}