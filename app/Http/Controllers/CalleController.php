<?php

namespace App\Http\Controllers;

use App\Models\Calle;
use Illuminate\Http\Request;

class CalleController extends Controller
{
    /**
     * Muestra la lista de calles.
     */
    public function index()
    {
        $calles = Calle::all();
        return view('calles.index', compact('calles'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        return view('calles.create');
    }

    /**
     * Almacena una nueva calle.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:calles,nombre'
        ]);

        $calle = Calle::create($request->all());

        if ($request->has('is_ajax')) {
            return response()->json(['id' => $calle->id, 'nombre' => $calle->nombre]);
        }

        return redirect()->route('calles.index')
            ->with('success', 'Calle creada exitosamente.');
    }

    /**
     * Show
     */
    public function show($id)
    {
    // 
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit($id)
    {
        $calle = Calle::findOrFail($id);
        return view('calles.edit', compact('calle'));
    }

    /**
     * Actualiza la calle seleccionada.
     */
    public function update(Request $request, $id)
    {
        $calle = Calle::findOrFail($id);

        $request->validate([
            'numero_calle' => 'nullable', // placeholder for consistency
            'habilitado' => 'boolean',
            'nombre' => 'required|string|max:255|unique:calles,nombre,' . $calle->id
        ]);

        $calle->update($request->all());

        return redirect()->route('calles.index')
            ->with('success', 'Calle actualizada exitosamente.');
    }

    /**
     * Deshabilita (borrado lógico) la calle.
     */
    public function destroy($id)
    {
        $calle = Calle::findOrFail($id);
        $calle->habilitado = 0;
        $calle->save();

        return redirect()->route('calles.index')
            ->with('success', 'Calle dada de baja exitosamente.');
    }
}