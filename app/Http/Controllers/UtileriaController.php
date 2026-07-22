<?php

namespace App\Http\Controllers;

use App\Models\Utileria;
use Illuminate\Http\Request;

class UtileriaController extends Controller
{
    /**
     * Muestra una lista del recurso.
     */
    public function index(Request $request)
    {
        $showDisabled = $request->get('ver_deshabilitadas', false);

        $query = Utileria::query();

        if (!$showDisabled) {
            $query->where('habilitado', 1);
        }

        $utilerias = $query->orderBy('nombre')->get();

        if ($request->wantsJson()) {
            return response()->json($utilerias, 200);
        }

        return view('utilerias.index', compact('utilerias', 'showDisabled'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        return view('utilerias.create');
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
     */
    public function store(Request $request)
    {
        // Si ya existe una utilería con ese nombre pero está dada de baja,
        // se la restaura (actualizando su stock) en lugar de rechazar el
        // nombre como duplicado.
        $deshabilitada = Utileria::where('nombre', trim((string) $request->input('nombre')))
            ->where('habilitado', 0)
            ->first();

        if ($deshabilitada) {
            $validated = $request->validate([
                'stock_total' => 'required|integer|min:0',
            ]);

            $deshabilitada->fill($validated);
            $deshabilitada->habilitado = 1;
            $deshabilitada->save();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Utilería restaurada correctamente',
                    'data' => $deshabilitada
                ], 200);
            }

            return redirect()->route('utilerias.index')
                ->with('success', "La utilería \"{$deshabilitada->nombre}\" ya existía y estaba dada de baja: se la restauró.");
        }

        $validated = $request->validate([
            'nombre' => 'required|string|unique:utilerias,nombre|max:255',
            'stock_total' => 'required|integer|min:0',
        ]);

        $utileria = Utileria::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Utilería creada correctamente',
                'data' => $utileria
            ], 201);
        }

        return redirect()->route('utilerias.index')->with('success', 'Utilería creada correctamente.');
    }

    /**
     * Muestra el recurso especificado.
     */
    public function show($id)
    {
        $utileria = Utileria::findOrFail($id);
        return response()->json($utileria, 200);
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     */
    public function edit($id)
    {
        $utileria = Utileria::findOrFail($id);
        return view('utilerias.edit', compact('utileria'));
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     */
    public function update(Request $request, $id)
    {
        $utileria = Utileria::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|unique:utilerias,nombre,' . $utileria->id . '|max:255',
            'stock_total' => 'required|integer|min:0',
        ]);

        $utileria->fill($validated);
        $utileria->habilitado = $request->has('habilitado');
        $utileria->save();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Utilería actualizada correctamente',
                'data' => $utileria
            ], 200);
        }

        return redirect()->route('utilerias.index')->with('success', 'Utilería actualizada correctamente.');
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     */
    public function destroy($id)
    {
        $utileria = Utileria::findOrFail($id);

        // No permitir la baja si la utilería está comprometida en algún alquiler
        // futuro que no esté cancelado (evita inconsistencias en reservas ya pactadas).
        $tieneAlquilerFuturo = $utileria->alquileres()
            ->whereDate('fecha_evento', '>=', now()->toDateString())
            ->where('estado', '!=', 'cancelado')
            ->exists();

        if ($tieneAlquilerFuturo) {
            $mensaje = "No se puede dar de baja \"{$utileria->nombre}\" porque está asignada a uno o más alquileres futuros. Reasigná o cancelá esas reservas antes de darla de baja.";

            if (request()->wantsJson()) {
                return response()->json(['message' => $mensaje], 422);
            }

            return redirect()->route('utilerias.index')->with('error', $mensaje);
        }

        $utileria->habilitado = 0;
        $utileria->save();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Utilería dada de baja correctamente'], 200);
        }

        return redirect()->route('utilerias.index')->with('success', 'Utilería dada de baja correctamente.');
    }
}