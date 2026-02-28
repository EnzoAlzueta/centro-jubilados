<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Cuota;
use App\Models\Socio;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);

        $query = Movimiento::whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio);

        $movimientos = (clone $query)->orderBy('fecha', 'desc')->get();

        $totalIngresos = (clone $query)->where('tipo', 'ingreso')->sum('monto');
        $totalEgresos = (clone $query)->where('tipo', 'egreso')->sum('monto');
        $saldoMensual = $totalIngresos - $totalEgresos;

        // Socios para el formulario de pago de cuotas
        $socios = Socio::where('habilitado', 1)->orderBy('apellido')->get();

        // Cuotas pagadas en este mes
        $cuotasPagadas = Cuota::with('socio')
            ->whereMonth('fecha_pago', $mes)
            ->whereYear('fecha_pago', $anio)
            ->where('pagado', true)
            ->get();

        return view('caja.index', compact(
            'movimientos',
            'totalIngresos',
            'totalEgresos',
            'saldoMensual',
            'mes',
            'anio',
            'socios',
            'cuotasPagadas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,egreso',
            'concepto' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'categoria' => 'required|string'
        ]);

        Movimiento::create($request->all());

        return redirect()->route('caja.index', ['mes' => Carbon::parse($request->fecha)->month, 'anio' => Carbon::parse($request->fecha)->year])
            ->with('success', 'Movimiento registrado correctamente.');
    }

    public function pagarCuota(Request $request)
    {
        $request->validate([
            'socio_id' => 'required|exists:socios,id',
            'mes' => 'required|integer|between:1,12',
            'anio' => 'required|integer',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date'
        ]);

        // Registrar o actualizar la cuota
        $cuota = Cuota::updateOrCreate(
        [
            'socio_id' => $request->socio_id,
            'mes' => $request->mes,
            'anio' => $request->anio
        ],
        [
            'monto' => $request->monto,
            'pagado' => true,
            'fecha_pago' => $request->fecha_pago
        ]
        );

        // Crear el movimiento en caja
        $socio = Socio::find($request->socio_id);

        // Evitar duplicar el movimiento si la cuota ya estaba paga
        // (En una app real, podrías querer manejar esto con mayor detalle)

        Movimiento::create([
            'fecha' => $request->fecha_pago,
            'tipo' => 'ingreso',
            'concepto' => "Pago de cuota: {$socio->apellido}, {$socio->nombre} ({$request->mes}/{$request->anio})",
            'monto' => $request->monto,
            'categoria' => 'cuota',
            'referencia_id' => $cuota->id,
            'referencia_type' => Cuota::class
        ]);

        return redirect()->route('caja.index', ['mes' => $request->mes, 'anio' => $request->anio])
            ->with('success', 'Cuota pagada y registrada en caja.');
    }

    public function cancelarPagoCuota(Request $request, $id)
    {
        // El $id es el ID de la Cuota
        $cuota = Cuota::with('socio')->findOrFail($id);

        try {
            DB::transaction(function () use ($cuota, $request) {
                // 1. Revertimos el estado de la cuota
                $cuota->update([
                    'pagado' => false,
                    'fecha_pago' => null
                ]);

                // 2. Registramos el movimiento de egreso (Devolución)
                // Usamos los datos de la cuota para el concepto
                Movimiento::create([
                    'fecha' => now()->toDateString(),
                    'tipo' => 'egreso',
                    'concepto' => "Anulación/Devolución Cuota: {$cuota->socio->apellido}, {$cuota->socio->nombre} ({$cuota->mes}/{$cuota->anio})",
                    'monto' => $cuota->monto,
                    'categoria' => 'cuota',
                    'referencia_id' => $cuota->id,
                    'referencia_type' => Cuota::class
                ]);
            });

            return back()->with('success', 'Pago de cuota cancelado y registrado como egreso en caja.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cancelar el pago: ' . $e->getMessage());
        }
    }
}