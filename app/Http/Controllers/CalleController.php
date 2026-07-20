<?php

namespace App\Http\Controllers;

use App\Models\Calle;
use Illuminate\Http\Request;

class CalleController extends Controller
{
    /**
     * Muestra la lista de calles.
     */
    public function index(Request $request)
    {
        $showDisabled = $request->get('ver_deshabilitadas', false);

        $query = Calle::query();

        if (!$showDisabled) {
            $query->where('habilitado', 1);
        }

        $calles = $query->orderBy('nombre')->get();

        return view('calles.index', compact('calles', 'showDisabled'));
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
     * No se permite la baja mientras haya socios con esa calle asignada:
     * se informa quiénes son para poder reasignarlos primero.
     */
    public function destroy($id)
    {
        $calle = Calle::findOrFail($id);

        $socios = $calle->socios()->orderBy('apellido')->orderBy('nombre')->get();

        if ($socios->isNotEmpty()) {
            $maxListado = 10;
            $lista = $socios->take($maxListado)
                ->map(fn ($s) => "{$s->apellido}, {$s->nombre} (N° {$s->numero_socio})")
                ->implode(' — ');

            if ($socios->count() > $maxListado) {
                $lista .= ' — y ' . ($socios->count() - $maxListado) . ' más';
            }

            return redirect()->route('calles.index')
                ->with('error', "No se puede dar de baja la calle \"{$calle->nombre}\" porque tiene {$socios->count()} socio(s) asignado(s): {$lista}. Reasignales otra calle antes de darla de baja.");
        }

        $calle->habilitado = 0;
        $calle->save();

        return redirect()->route('calles.index')
            ->with('success', 'Calle dada de baja exitosamente.');
    }
}