<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\Socio;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mes = $request->get('mes');
        $anio = $request->get('anio');
        $estado = $request->get('estado', 'activas'); // activas, anuladas, todas

        $query = Cuota::with('socio');

        if ($estado == 'anuladas') {
            $query->onlyTrashed();
        }
        elseif ($estado == 'todas') {
            $query->withTrashed();
        }

        if ($mes) {
            $query->where('mes', '=', $mes);
        }
        if ($anio) {
            $query->where('anio', '=', $anio);
        }

        // Se devuelve la colección completa (filtrada) y DataTables se encarga del
        // paginado/orden/búsqueda en el cliente, igual que el resto de las grillas del
        // sistema. Antes se usaba paginate(10) de Laravel a la vez que DataTables, lo que
        // provocaba que "Todos" mostrara sólo la primera página (año actual) y que el orden
        // por fecha se rompiera. Ordenamos por fecha de pago descendente por defecto.
        $cuotas = $query->orderBy('fecha_pago', 'desc')
            ->orderBy('anio', 'desc')
            ->orderBy('mes', 'desc')
            ->get();

        return view('cuotas.index', compact('cuotas', 'mes', 'anio', 'estado'));
    }

    /**
     * Get paid months for a socio and year.
     */
    public function getMesesPagados(Request $request, $socioId, $anio)
    {
        $meses = Cuota::where('socio_id', $socioId)
            ->where('anio', $anio)
            ->pluck('mes')
            ->toArray();

        return response()->json(['pagados' => $meses]);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        try {
            $cuota = Cuota::onlyTrashed()->findOrFail($id);
            $cuota->restore();

            return redirect()->route('cuotas.index', ['estado' => 'activas'])
                ->with('success', 'Cuota restaurada correctamente. Nota: La restauración no genera movimientos automáticos de caja.');
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al restaurar la cuota: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $socios = Socio::where('habilitado', 1)->orderBy('apellido')->get();
        return view('cuotas.create', compact('socios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'socio_id' => 'required|exists:socios,id',
            'meses' => 'required|array|min:1',
            'meses.*' => 'integer|between:1,12',
            'anio' => 'required|integer',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date|after_or_equal:1900-01-01'
        ]);

        // Validar que el socio esté habilitado
        $socio = Socio::findOrFail($request->socio_id);
        if (!$socio->habilitado) {
            return redirect()->back()->withInput()->with('error', 'No se pueden registrar cuotas para un socio deshabilitado.');
        }

        // Revisar si ya existen cuotas activas para los meses seleccionados
        $cuotasActivasExistentes = Cuota::where('socio_id', $request->socio_id)
            ->whereIn('mes', $request->meses)
            ->where('anio', $request->anio)
            ->get();

        if ($cuotasActivasExistentes->count() > 0) {
            return redirect()->back()->withInput()->with('error', 'El socio ya tiene registrada una cuota activa para alguno de los períodos seleccionados.');
        }

        try {
            DB::transaction(function () use ($request, $socio) {
                // El monto ingresado es POR MES, pero el movimiento de caja registrará el total o movimientos separados.
                $montoPorMes = $request->monto;
                
                // Obtener posibles cuotas anuladas previamente para los meses solicitados
                $cuotasAnuladas = Cuota::onlyTrashed()
                    ->where('socio_id', $request->socio_id)
                    ->whereIn('mes', $request->meses)
                    ->where('anio', $request->anio)
                    ->get()
                    ->keyBy('mes');

                foreach ($request->meses as $mes) {
                    if ($cuotasAnuladas->has($mes)) {
                        // Restaurar y actualizar cuota previamente anulada
                        $cuota = $cuotasAnuladas->get($mes);
                        $cuota->restore();
                        $cuota->update([
                            'monto' => $montoPorMes,
                            'pagado' => true,
                            'fecha_pago' => $request->fecha_pago
                        ]);
                    }
                    else {
                        // Crear cuota nueva
                        $cuota = Cuota::create([
                            'socio_id' => $request->socio_id,
                            'mes' => $mes,
                            'anio' => $request->anio,
                            'monto' => $montoPorMes,
                            'pagado' => true,
                            'fecha_pago' => $request->fecha_pago
                        ]);
                    }

                    // Registrar movimiento en caja correspondiente a este mes
                    Movimiento::create([
                        'fecha' => $request->fecha_pago,
                        'tipo' => 'ingreso',
                        'concepto' => "Pago de cuota: {$socio->apellido}, {$socio->nombre} (Mes {$mes}/{$request->anio})",
                        'monto' => $montoPorMes,
                        'categoria' => 'cuota',
                        'referencia_id' => $cuota->id,
                        'referencia_type' => Cuota::class
                    ]);
                }
            });

            return redirect()->route('cuotas.index')->with('success', 'Cuotas registradas y asimiladas en caja correctamente.');
        }
        catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al registrar las cuotas: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cuota = Cuota::findOrFail($id);
        $socios = Socio::where('habilitado', 1)->orderBy('apellido')->get();
        return view('cuotas.edit', compact('cuota', 'socios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'socio_id' => 'required|exists:socios,id',
            'mes' => 'required|integer|between:1,12',
            'anio' => 'required|integer',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date|after_or_equal:1900-01-01'
        ]);

        // Validar que el socio esté habilitado
        $socio = Socio::findOrFail($request->socio_id);
        if (!$socio->habilitado) {
            return redirect()->back()->withInput()->with('error', 'No se pueden registrar cuotas para un socio deshabilitado.');
        }

        $cuota = Cuota::findOrFail($id);

        // Validar que no se duplique periodo con OTRA cuota
        $existeOtra = Cuota::where('socio_id', $request->socio_id)
            ->where('mes', $request->mes)
            ->where('anio', $request->anio)
            ->where('id', '!=', $cuota->id)
            ->exists();

        if ($existeOtra) {
            return redirect()->back()->withInput()->with('error', 'Ese período ya se encuentra pagado en otra cuota.');
        }

        try {
            DB::transaction(function () use ($request, $cuota) {
                // Actualizar Cuota
                $cuota->update([
                    'socio_id' => $request->socio_id,
                    'mes' => $request->mes,
                    'anio' => $request->anio,
                    'monto' => $request->monto,
                    'fecha_pago' => $request->fecha_pago
                ]);

                // Actualizar Movimiento en Caja (Ingreso)
                $socio = Socio::find($request->socio_id);
                $movimiento = $cuota->movimiento;
                if ($movimiento) {
                    $movimiento->update([
                        'fecha' => $request->fecha_pago,
                        'concepto' => "Pago de cuota: {$socio->apellido}, {$socio->nombre} ({$request->mes}/{$request->anio})",
                        'monto' => $request->monto,
                    ]);
                }
            });

            return redirect()->route('cuotas.index')->with('success', 'Cuota actualizada correctamente.');
        }
        catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar la cuota: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $cuota = Cuota::with('socio')->findOrFail($id);
        $conReintegro = $request->has('reintegro') && $request->reintegro == '1';

        try {
            DB::transaction(function () use ($cuota, $conReintegro) {
                if ($conReintegro) {
                    // Generar movimiento de egreso por la anulación para mantener auditoría
                    Movimiento::create([
                        'fecha' => now()->toDateString(),
                        'tipo' => 'egreso',
                        'concepto' => "Anulación/Devolución Cuota: {$cuota->socio->apellido}, {$cuota->socio->nombre} ({$cuota->mes}/{$cuota->anio})",
                        'monto' => $cuota->monto,
                        'categoria' => 'cuota',
                        'referencia_id' => $cuota->id,
                        'referencia_type' => Cuota::class
                    ]);
                }

                // Borrado Lógico de la cuenta
                $cuota->delete();
            });

            $mensaje = $conReintegro
                ? 'Cuota eliminada lógicamente y reintegro asimilado en caja.'
                : 'Cuota eliminada lógicamente (sin reintegro de dinero).';

            return redirect()->route('cuotas.index')->with('success', $mensaje);
        }
        catch (\Exception $e) {
            return redirect()->route('cuotas.index')->with('error', 'Error al eliminar la cuota: ' . $e->getMessage());
        }
    }
}