<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Socio;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener total de socios habilitados
        $totalSocios = Socio::where('habilitado', 1)->count();

        // Obtener ingresos del mes (ejemplo simplificado, sumando precio o seña_pagada)
        $ingresosMes = Alquiler::whereMonth('fecha_evento', now()->month)
            ->whereYear('fecha_evento', now()->year)
            ->sum('seña_pagada');

        // Obtener próximos 10 alquileres
        $alquileres = Alquiler::with(['socio', 'sector'])
            ->where('fecha_evento', '>=', now()->toDateString())
            ->orderBy('fecha_evento', 'asc')
            ->take(10)
            ->get();

        return view('dashboard', compact('totalSocios', 'ingresosMes', 'alquileres'));
    }
}