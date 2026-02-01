<?php

namespace App\Http\Controllers;

use App\Models\Utileria;
use Illuminate\Http\Request;

class UtileriaController extends Controller
{
    /**
     * GET /api/utilerias
     * Devuelve la lista completa de sectores.
     * Ideal para llenar el <select> en el formulario de alta de socio.
     */
    public function index()
    {
        $utilerias = Utileria::all();
        return response()->json($utilerias, 200);
    }

    /**
     * POST /api/utilerias
     * Crea un utileria nuevo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:utilerias,nombre|max:255',
        ]);

        $utileria = Utileria::create($request->all());

        return response()->json([
            'message' => 'utileria creado correctamente',
            'data' => $utileria
        ], 201);
    }

    /**
     * GET /api/utilerias/{id}
     */
    public function show($id)
    {
        $utileria = Utileria::findOrFail($id);
        return response()->json($utileria, 200);
    }

    /**
     * PUT /api/utilerias/{id}
     */
    public function update(Request $request, $id)
    {
        $utileria = Utileria::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|unique:utilerias,nombre,'.$utileria->id.'|max:255',
        ]);

        $utileria->update($request->all());

        return response()->json([
            'message' => 'utileria actualizado',
            'data' => $utileria
        ], 200);
    }

    /**
     * DELETE /api/utilerias/{id}
     */
    public function destroy($id)
    {
        $utileria = Utileria::findOrFail($id);
        
        // Opcional: validar si hay socios viviendo aquí antes de borrar
        // if($utileria->socios()->exists()) { return error... }

        $utileria->delete();

        return response()->json(['message' => 'utileria eliminado'], 200);
    }
}
