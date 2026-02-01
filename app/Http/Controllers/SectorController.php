<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    /**
     * GET /api/sectors
     * Devuelve la lista completa de sectores.
     * Ideal para llenar el <select> en el formulario de alta de socio.
     */
    public function index()
    {
        $sectors = Sector::all();
        return response()->json($sectors, 200);
    }

    /**
     * POST /api/sectors
     * Crea un sector nuevo.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:sectors,nombre|max:255',
        ]);

        $sector = Sector::create($request->all());

        return response()->json([
            'message' => 'Sector creado correctamente',
            'data' => $sector
        ], 201);
    }

    /**
     * GET /api/sectors/{id}
     */
    public function show($id)
    {
        $sector = Sector::findOrFail($id);
        return response()->json($sector, 200);
    }

    /**
     * PUT /api/sectors/{id}
     */
    public function update(Request $request, $id)
    {
        $sector = Sector::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|unique:sectors,nombre,'.$sector->id.'|max:255',
        ]);

        $sector->update($request->all());

        return response()->json([
            'message' => 'Sector actualizado',
            'data' => $sector
        ], 200);
    }

    /**
     * DELETE /api/sectors/{id}
     */
    public function destroy($id)
    {
        $sector = Sector::findOrFail($id);
        
        // Opcional: validar si hay socios viviendo aquí antes de borrar
        // if($sector->socios()->exists()) { return error... }

        $sector->delete();

        return response()->json(['message' => 'Sector eliminado'], 200);
    }
}
