<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    /**
     * Muestra una lista de sectores.
     */
    public function index(Request $request)
    {
        $showDisabled = $request->get('ver_deshabilitadas', false);

        $query = Sector::query();

        if (!$showDisabled) {
            $query->where('habilitado', 1);
        }

        $sectores = $query->orderBy('nombre')->get();

        if ($request->wantsJson()) {
            return response()->json($sectores, 200);
        }

        return view('sectores.index', compact('sectores', 'showDisabled'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        return view('sectores.create');
    }

    /**
     * POST /api/sectors o almacena desde vista
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:sectors,nombre|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'precio_base' => 'nullable|numeric|min:0',
        ]);

        $sector = Sector::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Sector creado correctamente',
                'data' => $sector
            ], 201);
        }

        return redirect()->route('sectores.index')->with('success', 'Sector creado correctamente.');
    }

    /**
     * Muestra el recurso especificado.
     */
    public function show($id)
    {
        $sector = Sector::findOrFail($id);
        return response()->json($sector, 200);
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     */
    public function edit($id)
    {
        $sector = Sector::findOrFail($id);
        return view('sectores.edit', compact('sector'));
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     */
    public function update(Request $request, $id)
    {
        $sector = Sector::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|unique:sectors,nombre,' . $sector->id . '|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'precio_base' => 'nullable|numeric|min:0',
        ]);

        $sector->fill($validated);
        $sector->habilitado = $request->has('habilitado');
        $sector->save();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Sector actualizado',
                'data' => $sector
            ], 200);
        }

        return redirect()->route('sectores.index')->with('success', 'Sector actualizado correctamente.');
    }

    /**
     * Elimina el recurso especificado del almacenamiento (lógico).
     * No se permite la baja si el sector tiene alquileres futuros
     * no cancelados (mismo criterio que utilería).
     */
    public function destroy($id)
    {
        $sector = Sector::findOrFail($id);

        $tieneAlquilerFuturo = $sector->alquileres()
            ->whereDate('fecha_evento', '>=', now()->toDateString())
            ->where('estado', '!=', 'cancelado')
            ->exists();

        if ($tieneAlquilerFuturo) {
            $mensaje = "No se puede dar de baja \"{$sector->nombre}\" porque tiene uno o más alquileres futuros. Reasigná o cancelá esas reservas antes de darlo de baja.";

            if (request()->wantsJson()) {
                return response()->json(['message' => $mensaje], 422);
            }

            return redirect()->route('sectores.index')->with('error', $mensaje);
        }

        $sector->habilitado = 0;
        $sector->save();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Sector dado de baja correctamente'], 200);
        }

        return redirect()->route('sectores.index')->with('success', 'Sector dado de baja correctamente.');
    }
}