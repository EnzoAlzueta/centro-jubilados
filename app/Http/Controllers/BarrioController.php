<?php

namespace App\Http\Controllers;

use App\Models\Barrio;
use Illuminate\Http\Request;

class BarrioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Trae todos los barrios de la base de datos
        $barrios = \App\Models\Barrio::all(); 
        return view('barrios.index', compact('barrios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barrios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        // 2. Guardar
        \App\Models\Barrio::create($request->all());

        // 3. Redireccionar
        return redirect()->route('barrios.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barrio $barrio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barrio $barrio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barrio $barrio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barrio $barrio)
    {
        //
    }
}
