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
        // Si ya existe un barrio con ese nombre pero está dado de baja,
        // se lo restaura en lugar de rechazar el nombre como duplicado.
        $deshabilitado = Barrio::where('nombre', trim((string) $request->input('nombre')))
            ->where('habilitado', 0)
            ->first();

        if ($deshabilitado) {
            $deshabilitado->habilitado = 1;
            $deshabilitado->save();

            if ($request->has('is_ajax')) {
                return response()->json(['id' => $deshabilitado->id, 'nombre' => $deshabilitado->nombre]);
            }

            return redirect()->route('barrios.index')
                ->with('success', "El barrio \"{$deshabilitado->nombre}\" ya existía y estaba dado de baja: se lo restauró.");
        }

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
     * No se permite la baja mientras haya socios con ese barrio asignado:
     * se informa quiénes son para poder reasignarlos primero.
     */
    public function destroy(Barrio $barrio)
    {
        $socios = $barrio->socios()->orderBy('apellido')->orderBy('nombre')->get();

        if ($socios->isNotEmpty()) {
            $maxListado = 10;
            $lista = $socios->take($maxListado)
                ->map(fn ($s) => "{$s->apellido}, {$s->nombre} (N° {$s->numero_socio})")
                ->implode(' — ');

            if ($socios->count() > $maxListado) {
                $lista .= ' — y ' . ($socios->count() - $maxListado) . ' más';
            }

            return redirect()->route('barrios.index')
                ->with('error', "No se puede dar de baja el barrio \"{$barrio->nombre}\" porque tiene {$socios->count()} socio(s) asignado(s): {$lista}. Reasignales otro barrio antes de darlo de baja.");
        }

        $barrio->habilitado = 0;
        $barrio->save();

        return redirect()->route('barrios.index')
            ->with('success', 'Barrio dado de baja correctamente.');
    }
}