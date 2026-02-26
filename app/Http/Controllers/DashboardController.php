<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Movimiento;
use App\Models\Socio;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener total de socios habilitados
        $totalSocios = Socio::where('habilitado', 1)->count();

        // Obtener ingresos y egresos del mes desde Caja (Movimientos)
        $query = Movimiento::whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year);

        $totalIngresos = (clone $query)->where('tipo', 'ingreso')->sum('monto');
        $totalEgresos = (clone $query)->where('tipo', 'egreso')->sum('monto');
        $saldoMes = $totalIngresos - $totalEgresos;

        // Obtener próximos 10 alquileres
        $alquileres = Alquiler::with(['socio', 'sector'])
            ->where('fecha_evento', '>=', now()->toDateString())
            ->orderBy('fecha_evento', 'asc')
            ->take(10)
            ->get();

        return view('dashboard', compact('totalSocios', 'saldoMes', 'alquileres'));
    }
}