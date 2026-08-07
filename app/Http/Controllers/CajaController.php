<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Cuota;
use App\Models\Socio;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);

        $query = Movimiento::with('categoria')->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio);

        $movimientos = (clone $query)->orderBy('fecha', 'desc')->get();

        $totalIngresos = (clone $query)->where('tipo', 'ingreso')->sum('monto');
        $totalEgresos = (clone $query)->where('tipo', 'egreso')->sum('monto');
        $saldoMensual = $totalIngresos - $totalEgresos;
        $categorias = Categoria::where('habilitado', true)->orderBy('nombre')->get();
        return view('caja.index', compact(
            'movimientos',
            'totalIngresos',
            'totalEgresos',
            'saldoMensual',
            'mes',
            'anio',
            'categorias'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,egreso',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categoria,id'
        ]);

        Movimiento::create($request->all());

        return redirect()->route('caja.index', ['mes' => Carbon::parse($request->fecha)->month, 'anio' => Carbon::parse($request->fecha)->year])
            ->with('success', 'Movimiento registrado correctamente.');
    }
    
    
}