<?php

namespace App\Http\Controllers;

use App\Models\Alquiler;
use App\Models\Utileria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importante para las transacciones

class AlquilerController extends Controller
{
    /**
     * GET /api/alquileres
     * Lista todos los alquileres con sus relaciones.
     */
    public function index()
    {
        // Traemos todo junto para no hacer 100 consultas a la base
        $alquileres = Alquiler::with(['sector', 'socio', 'utilerias'])->latest()->get();
        return response()->json($alquileres, 200);
    }

    /**
     * POST /api/alquileres
     * Crea una nueva reserva.
     */
    public function store(Request $request)
    {
        // 1. Validaciones "pesadas"
        $validated = $request->validate([
            'sector_id' => 'required|exists:sectors,id',
            'fecha_evento' => 'required|date|after:today', // No se puede reservar en el pasado
            'tipo_evento' => 'required|string',
            'precio_pactado' => 'required|numeric|min:0',
            'seña_pagada' => 'numeric|min:0',
            
            // Lógica: O es socio O es externo
            'socio_id' => 'nullable|exists:socios,id',
            'solicitante_externo' => 'nullable|required_without:socio_id|string',
            'dni_solicitante_externo' => 'nullable|required_without:socio_id|string',

            // Utilería: Array de objetos [{id: 1, cantidad: 50}, {id: 2, cantidad: 10}]
            'utilerias' => 'array',
            'utilerias.*.id' => 'exists:utilerias,id',
            'utilerias.*.cantidad' => 'integer|min:1'
        ]);

        // 2. Usamos una Transacción (DB::transaction)
        // Esto asegura que si falla el guardado de las sillas, NO se cree el alquiler
        try {
            return DB::transaction(function () use ($validated, $request) {
                
                // A. Crear el Alquiler
                $alquiler = Alquiler::create([
                    'sector_id' => $validated['sector_id'],
                    'fecha_evento' => $validated['fecha_evento'],
                    'tipo_evento' => $validated['tipo_evento'],
                    'precio_pactado' => $validated['precio_pactado'],
                    'seña_pagada' => $request->seña_pagada ?? 0,
                    'estado' => 'reservado', // Estado inicial por defecto
                    // Campos condicionales
                    'socio_id' => $request->socio_id,
                    'solicitante_externo' => $request->solicitante_externo,
                    'dni_solicitante_externo' => $request->dni_solicitante_externo,
                ]);

                // B. Guardar las utilerias (Sillas, Mesas) en la tabla pivote
                if (!empty($request->utilerias)) {
                    foreach ($request->utilerias as $item) {
                        // attach recibe el ID y un array con los campos extra de la pivote
                        $alquiler->utilerias()->attach($item['id'], ['cantidad' => $item['cantidad']]);
                        
                        // Opcional: Aquí podrías restar stock de la tabla utilerias si quisieras
                        // Utileria::find($item['id'])->decrement('stock_total', $item['cantidad']);
                    }
                }

                // C. Devolver respuesta con todo cargado
                return response()->json([
                    'message' => 'Alquiler reservado con éxito',
                    'data' => $alquiler->load('utilerias')
                ], 201);
            });

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al procesar la reserva: ' . $e->getMessage()], 500);
        }
    }

    /**
     * GET /api/alquileres/{id}
     */
    public function show($id)
    {
        $alquiler = Alquiler::with(['sector', 'socio', 'utilerias'])->findOrFail($id);
        return response()->json($alquiler, 200);
    }

    /**
     * PUT /api/alquileres/{id}
     * Actualizar estado o datos.
     */
    public function update(Request $request, $id)
    {
        $alquiler = Alquiler::findOrFail($id);
        
        // Aquí puedes validar lo que quieras actualizar
        $alquiler->update($request->except('utilerias'));

        // Si mandan utilerias nuevas, sincronizamos (borra las viejas y pone las nuevas)
        if ($request->has('utilerias')) {
            $syncData = [];
            foreach ($request->utilerias as $item) {
                $syncData[$item['id']] = ['cantidad' => $item['cantidad']];
            }
            $alquiler->utilerias()->sync($syncData);
        }

        return response()->json(['message' => 'Alquiler actualizado', 'data' => $alquiler->fresh('utilerias')], 200);
    }

    /**
     * DELETE /api/alquileres/{id}
     */
    public function destroy($id)
    {
        $alquiler = Alquiler::findOrFail($id);
        
        // Al borrar el alquiler, Laravel borra solo las filas de la tabla pivote automáticamente
        // gracias al 'onDelete cascade' que pusimos en la migración.
        $alquiler->delete();

        return response()->json(['message' => 'Alquiler eliminado'], 200);
    }
}