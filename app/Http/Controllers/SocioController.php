<?php

namespace App\Http\Controllers;

use App\Models\Barrio;
use App\Models\Calle; // Added this line
use App\Models\Socio;
use Illuminate\Http\Request;


class SocioController extends Controller
{
    /**
     * Muestra una lista del recurso.
     */
    public function index(Request $request)
    {
        $showDisabled = $request->get('ver_deshabilitados', false);

        $query = Socio::with('barrio');

        if (!$showDisabled) {
            $query->where('habilitado', 1);
        }

        $socios = $query->get();

        return view('socios.index', compact('socios', 'showDisabled'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     */
    public function create()
    {
        $barrios = Barrio::all();
        $calles = Calle::where('habilitado', true)->get();
        return view('socios.create', compact('barrios', 'calles'));
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_socio' => 'required|string|unique:socios,numero_socio',
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|unique:socios,dni|max:20',
            'barrio_id' => 'required|exists:barrios,id',
            'calle_id' => 'required|exists:calles,id', // Ensured this is calle_id
            'altura' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date|after_or_equal:1900-01-01',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $data = $request->all();

        Socio::create($data);

        return redirect()->route('socios.index')
            ->with('success', 'Socio creado correctamente');
    }

    /**
     * Muestra el recurso especificado.
     */
    public function show(string $id)
    {
        $socio = Socio::with(['barrio', 'calle'])->findOrFail($id);
        return view('socios.show', compact('socio'));
    }

    /**
     * Muestra el formulario para editar el recurso especificado.
     */
    public function edit(string $id)
    {
        $socio = Socio::findOrFail($id);
        $barrios = Barrio::all();
        $calles = Calle::where('habilitado', true)->get(); // Added this line
        return view('socios.edit', compact('socio', 'barrios', 'calles')); // Added 'calles' to compact
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento.
     */
    public function update(Request $request, string $id)
    {
        $socio = Socio::findOrFail($id);

        $request->validate([
            'numero_socio' => 'required|string|unique:socios,numero_socio,' . $socio->id,
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'dni' => 'required|string|unique:socios,dni,' . $socio->id . '|max:20',
            'barrio_id' => 'required|exists:barrios,id',
            'calle_id' => 'required|exists:calles,id', // Changed 'calle' to 'calle_id' and added exists rule
            'altura' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date|after_or_equal:1900-01-01',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $socio->update($request->all());

        return redirect()->route('socios.index')
            ->with('success', 'Socio actualizado correctamente');
    }

    /**
     * Elimina el recurso especificado del almacenamiento.
     */
    public function destroy(string $id)
    {
        $socio = Socio::findOrFail($id);
        $socio->habilitado = 0;
        $socio->save();

        return redirect()->route('socios.index')
            ->with('success', 'Socio dado de baja correctamente');
    }

    /**
     * Genera la cartola de pagos en formato PDF.
     */
    public function cartola(Request $request, string $id)
    {
        $anio = $request->get('anio', date('Y'));

        $socio = Socio::with(['barrio', 'calle', 'cuotas' => function ($query) use ($anio) {
            $query->where('anio', '=', $anio)
                ->where('pagado', '=', true);
        }])->findOrFail($id);

        $cuotasPagas = $socio->cuotas->keyBy('mes');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('socios.pdf.cartola', compact('socio', 'cuotasPagas', 'anio'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Cartola_Socio_' . $socio->numero_socio . '_' . $anio . '.pdf');
    }
}