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
    public function index(Request $request)
    {
        $showDisabled = $request->get('ver_deshabilitadas', false);

        $query = Barrio::query();

        if (!$showDisabled) {
            $query->where('habilitado', 1);
        }

        $barrios = $query->orderBy('nombre')->get();

        return view('barrios.index', compact('barrios', 'showDisabled'));
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
     * Deshabilita (borrado lógico) el barrio.
     * Los socios existentes conservan su barrio; solo deja de estar
     * disponible para nuevas altas hasta que se lo restaure desde Editar.
     */
    public function destroy(Barrio $barrio)
    {
        $barrio->habilitado = 0;
        $barrio->save();

        return redirect()->route('barrios.index')
            ->with('success', 'Barrio dado de baja correctamente.');
    }
}