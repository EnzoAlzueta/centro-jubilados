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
    /**
     * Muestra una lista del recurso.
     */
    public function index()
    {
        $barrios = Barrio::all();
        return view('barrios.index', compact('barrios'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        return view('barrios.create');
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|unique:barrios,nombre|max:255',
        ]);

        $barrio = Barrio::create($request->all());

        if ($request->has('is_ajax')) {
            return response()->json(['id' => $barrio->id, 'nombre' => $barrio->nombre]);
        }

        return redirect()->route('barrios.index')
            ->with('success', 'Barrio creado correctamente');
    }

    /**
     * Muestra el recurso especificado.
     */
    public function show($id)
    {
        $barrio = Barrio::findOrFail($id);
        return view('barrios.show', compact('barrio'));
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     */
    public function edit($id)
    {
        $barrio = Barrio::findOrFail($id);
        return view('barrios.edit', compact('barrio'));
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     */
    public function update(Request $request, $id)
    {
        $barrio = Barrio::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|unique:barrios,nombre,' . $barrio->id . '|max:255',
        ]);

        $barrio->update($request->all());

        return redirect()->route('barrios.index')
            ->with('success', 'Barrio actualizado correctamente');
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     */
    public function destroy(Barrio $barrio)
    {
        if ($barrio->socios()->exists()) {
            return redirect()->route('barrios.index')
                ->with('error', 'No se puede eliminar el barrio porque tiene socios asociados (incluyendo deshabilitados).');
        }

        $barrio->delete();

        return redirect()->route('barrios.index')
            ->with('success', 'Barrio eliminado correctamente');
    }
}