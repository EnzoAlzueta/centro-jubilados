<?php

namespace App\Http\Controllers;

use App\Models\Barrio;
use Illuminate\Http\Request;

class BarrioController extends Controller
{
    /**
     * GET /api/barrios
     * Devuelve la lista completa de barrios.
     * Ideal para llenar el <select> en el formulario de alta de socio.
     */
    public function index()
    {
        $barrios = Barrio::all();
        return response()->json($barrios, 200);
    }

    /**
     * POST /api/barrios
     * Crea un barrio nuevo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:barrios,nombre|max:255',
        ]);

        $barrio = Barrio::create($request->all());

        return response()->json([
            'message' => 'Barrio creado correctamente',
            'data' => $barrio
        ], 201);
    }

    /**
     * GET /api/barrios/{id}
     */
    public function show($id)
    {
        $barrio = Barrio::findOrFail($id);
        return response()->json($barrio, 200);
    }

    /**
     * PUT /api/barrios/{id}
     */
    public function update(Request $request, $id)
    {
        $barrio = Barrio::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|unique:barrios,nombre,'.$barrio->id.'|max:255',
        ]);

        $barrio->update($request->all());

        return response()->json([
            'message' => 'Barrio actualizado',
            'data' => $barrio
        ], 200);
    }

    /**
     * DELETE /api/barrios/{id}
     */
    public function destroy($id)
    {
        $barrio = Barrio::findOrFail($id);
        
        // Opcional: Se podría validar si hay socios viviendo aquí antes de borrar
        // if($barrio->socios()->exists()) { return error... }

        $barrio->delete();

        return response()->json(['message' => 'Barrio eliminado'], 200);
    }
}