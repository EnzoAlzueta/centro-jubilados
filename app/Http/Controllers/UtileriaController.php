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
        if ($request->wantsJson()) {
            $utilerias = Utileria::all();
            return response()->json($utilerias, 200);
        }

        $utilerias = Utileria::orderBy('nombre')->get();
        return view('utilerias.index', compact('utilerias'));
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

        $utileria->update($validated);

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
        $utileria->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Utilería eliminada correctamente'], 200);
        }

        return redirect()->route('utilerias.index')->with('success', 'Utilería eliminada correctamente.');
    }
}