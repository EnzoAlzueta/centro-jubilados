<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Utileria;
use App\Models\Socio;
use App\Models\Sector;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlquilerController extends Controller
{
    /**
     * Muestra la vista principal con el calendario y el formulario.
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $alquileres = Alquiler::with(['sector', 'socio', 'utilerias'])->latest()->get();
            return response()->json($alquileres, 200);
        }

        $socios = Socio::where('habilitado', 1)->orderBy('apellido')->get();
        $sectores = Sector::where('habilitado', 1)->get();
        $utilerias = Utileria::all();

        return view('alquileres.index', compact('socios', 'sectores', 'utilerias'));
    }

    /**
     * Endpoint para obtener eventos en formato JSON para FullCalendar.
     */
    public function getEvents()
    {
        $alquileres = Alquiler::with(['socio', 'sector'])->get();

        $events = $alquileres->map(function ($alquiler) {
            $title = ($alquiler->socio_id ? $alquiler->socio->apellido : $alquiler->solicitante_externo) . " - " . $alquiler->tipo_evento;
            return [
                'id' => $alquiler->id,
                'title' => $title,
                'start' => $alquiler->fecha_evento,
                'color' => $this->getColorByEstado($alquiler->estado),
                'extendedProps' => [
                    'solicitante' => $alquiler->socio_id ? $alquiler->socio->apellido . ", " . $alquiler->socio->nombre : $alquiler->solicitante_externo,
                    'sector' => $alquiler->sector->nombre,
                    'estado' => $alquiler->estado,
                    'precio' => $alquiler->precio,
                    'seña' => $alquiler->seña_pagada
                ]
            ];
        });

        return response()->json($events);
    }

    /**
     * Crea una nueva reserva.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sector_id' => 'required|exists:sectors,id',
            'fecha_evento' => 'required|date|after_or_equal:1900-01-01',
            'tipo_evento' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'seña_pagada' => 'numeric|min:0',
            'socio_id' => 'nullable|exists:socios,id',
            'solicitante_externo' => 'nullable|required_without:socio_id|string',
            'dni_solicitante_externo' => 'nullable|required_without:socio_id|string',
            'utilerias' => 'array',
            'utilerias.*.id' => 'exists:utilerias,id',
            'utilerias.*.cantidad' => 'nullable|integer|min:0'
        ]);

        // Validar disponibilidad: Solo 1 sector por día (excluyendo cancelados)
        $existeReserva = Alquiler::whereDate('fecha_evento', '=', $validated['fecha_evento'])
            ->where('estado', '!=', 'cancelado')
            ->exists();

        if ($existeReserva) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Ya existe una reserva para la fecha seleccionada.'], 422);
            }
            return back()->with('error', 'Ya existe una reserva para la fecha seleccionada. Por favor, elige otro día.')->withInput();
        }

        // Validar stock de utilerías
        if (!empty($request->utilerias)) {
            foreach ($request->utilerias as $item) {
                if (isset($item['cantidad']) && $item['cantidad'] > 0) {
                    $util = Utileria::find($item['id']);
                    if ($util && $item['cantidad'] > $util->stock_total) {
                        $errorMsg = "Stock insuficiente de {$util->nombre}. Disponible: {$util->stock_total}";
                        if ($request->wantsJson()) {
                            return response()->json(['error' => $errorMsg], 422);
                        }
                        return back()->with('error', $errorMsg)->withInput();
                    }
                }
            }
        }

        try {
            return DB::transaction(function () use ($validated, $request) {
                $alquiler = Alquiler::create([
                    'sector_id' => $validated['sector_id'],
                    'fecha_evento' => $validated['fecha_evento'],
                    'tipo_evento' => $validated['tipo_evento'],
                    'precio' => $validated['precio'],
                    'seña_pagada' => $request->seña_pagada ?? 0,
                    'estado' => 'reservado',
                    'socio_id' => $request->socio_id,
                    'solicitante_externo' => $request->solicitante_externo,
                    'dni_solicitante_externo' => $request->dni_solicitante_externo,
                ]);

                if (!empty($request->utilerias)) {
                    foreach ($request->utilerias as $item) {
                        if (isset($item['cantidad']) && $item['cantidad'] > 0) {
                            $alquiler->utilerias()->attach($item['id'], ['cantidad' => $item['cantidad']]);
                        }
                    }
                }

                if ($alquiler->seña_pagada > 0) {
                    Movimiento::create([
                        'fecha' => now()->toDateString(),
                        'tipo' => 'ingreso',
                        'concepto' => "Seña Alquiler: " . ($alquiler->socio_id ? $alquiler->socio->apellido : $alquiler->solicitante_externo) . " - " . $alquiler->tipo_evento,
                        'monto' => $alquiler->seña_pagada,
                        'categoria' => 'alquiler',
                        'referencia_id' => $alquiler->id,
                        'referencia_type' => Alquiler::class
                    ]);
                }

                if ($request->wantsJson()) {
                    return response()->json(['message' => 'Alquiler reservado con éxito', 'data' => $alquiler], 201);
                }

                return redirect()->route('alquileres.index')->with('success', 'Alquiler reservado con éxito.');
            });
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Error al procesar la reserva: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $alquiler = Alquiler::with(['sector', 'socio', 'utilerias'])->findOrFail($id);
        return response()->json($alquiler, 200);
    }

    public function update(Request $request, $id)
    {
        $alquiler = Alquiler::findOrFail($id);
        
        $request->validate([
            'sector_id' => 'required|exists:sectors,id',
            'fecha_evento' => 'required|date|after_or_equal:1900-01-01',
            'tipo_evento' => 'required|string',
            'precio' => 'required|numeric|min:0',
        ]);

        // Validar disponibilidad: Solo 1 sector por día (excluyendo cancelados y el actual)
        $existeReserva = Alquiler::whereDate('fecha_evento', '=', $request->fecha_evento)
            ->where('estado', '!=', 'cancelado')
            ->where('id', '!=', $id)
            ->exists();

        if ($existeReserva) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Ya existe una reserva para la fecha seleccionada.'], 422);
            }
            return back()->with('error', 'Ya existe una reserva para la fecha seleccionada. Por favor, elige otro día.')->withInput();
        }

        // Validar stock de utilerías
        if ($request->has('utilerias')) {
            foreach ($request->utilerias as $item) {
                if (isset($item['cantidad']) && $item['cantidad'] > 0) {
                    $util = Utileria::find($item['id'] ?? null);
                    if ($util && $item['cantidad'] > $util->stock_total) {
                        $errorMsg = "Stock insuficiente de {$util->nombre}. Disponible: {$util->stock_total}";
                        if ($request->wantsJson()) {
                            return response()->json(['error' => $errorMsg], 422);
                        }
                        return back()->with('error', $errorMsg)->withInput();
                    }
                }
            }
        }

        $alquiler->update($request->except(['utilerias', '_method', '_token']));

        if ($request->has('utilerias')) {
            $syncData = [];
            foreach ($request->utilerias as $item) {
                if (isset($item['id'])) {
                    $syncData[$item['id']] = ['cantidad' => $item['cantidad'] ?? 0];
                }
            }
            $alquiler->utilerias()->sync($syncData);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Alquiler actualizado', 'data' => $alquiler], 200);
        }
        return redirect()->route('alquileres.index')->with('success', 'Alquiler actualizado.');
    }

    public function destroy(Request $request, $id)
    {
        $alquiler = Alquiler::findOrFail($id);

        try {
            DB::transaction(function () use ($alquiler, $request) {
                $alquiler->estado = 'cancelado';
                $alquiler->save();

                // Si se solicita devolución de la seña/pago y hay un monto válido
                if ($request->has('devolucion') && $request->devolucion == true && $alquiler->seña_pagada > 0) {
                    Movimiento::create([
                        'fecha' => now()->toDateString(),
                        'tipo' => 'egreso',
                        'concepto' => "Devolución Cancelación: " . ($alquiler->socio_id ? $alquiler->socio->apellido : $alquiler->solicitante_externo) . " - " . $alquiler->tipo_evento,
                        'monto' => $alquiler->seña_pagada,
                        'categoria' => 'alquiler',
                        'referencia_id' => $alquiler->id,
                        'referencia_type' => Alquiler::class
                    ]);
                }
            });

            return response()->json(['message' => 'Alquiler cancelado exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cancelar: ' . $e->getMessage()], 500);
        }
    }

    public function registrarPago(Request $request, $id)
    {
        $alquiler = Alquiler::findOrFail($id);
        $pendiente = $alquiler->precio - $alquiler->seña_pagada;

        $validated = $request->validate([
            'monto' => 'required|numeric|min:0.01|max:'.$pendiente,
        ]);

        try {
            return DB::transaction(function () use ($validated, $alquiler) {
                $alquiler->seña_pagada += $validated['monto'];
                
                if ($alquiler->seña_pagada >= $alquiler->precio) {
                    $alquiler->estado = 'confirmado';
                }
                $alquiler->save();

                Movimiento::create([
                    'fecha' => now()->toDateString(),
                    'tipo' => 'ingreso',
                    'concepto' => "Pago Saldo Alquiler: " . ($alquiler->socio_id ? $alquiler->socio->apellido : $alquiler->solicitante_externo) . " - " . $alquiler->tipo_evento,
                    'monto' => $validated['monto'],
                    'categoria' => 'alquiler',
                    'referencia_id' => $alquiler->id,
                    'referencia_type' => Alquiler::class
                ]);

                return response()->json([
                    'message' => 'Pago registrado con éxito',
                    'nuevo_pendiente' => $alquiler->precio - $alquiler->seña_pagada,
                    'estado' => $alquiler->estado
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al registrar el pago: ' . $e->getMessage()], 500);
        }
    }

    private function getColorByEstado($estado)
    {
        return match ($estado) {
            'reservado' => '#0dcaf0',  // info (cyan)
            'confirmado' => '#198754', // success (green)
            'cancelado' => '#dc3545',  // danger (red)
            'finalizado' => '#6c757d', // secondary (grey)
            default => '#6c757d'
        };
    }
}