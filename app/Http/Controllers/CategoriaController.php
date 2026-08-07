<?php

namespace App\Http\Controllers;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request) {
        $showDisabled = $request->get('ver_deshabilitadas', false);
        $query = Categoria::query();

        if(!$showDisabled) {
            $query->where('habilitado', 1);
        }

        $categorias = $query->orderBy('nombre')->get();

        return view('categorias.index', compact('categorias', 'showDisabled'));
    }

    public function create() {
        return view('categorias.create');
    }

    public function store(Request $request) {

        $deshabilitado = Categoria::where('nombre', trim((string) $request->input('nombre')))
        ->where('habilitado', 0)
        ->first();

        if($deshabilitado) {
            $deshabilitado-> habilitado = 1;
            $deshabilitado->save();
        

            if($request->has('is_ajax')) {
                return response()->json(['id' => $deshabilitado->id, 'nombre'=> $deshabilitado->nombre]);
            }

            return redirect()->route('categorias.index')
            ->with('success', "La categoría \"{$deshabilitado->nombre}\" ya existía y estaba dado de baja: se la restauró");
        }

        $request->validate([
            'nombre' => 'required|string|unique:categoria,nombre|max:255'
        ]);

        $categoria = Categoria::create($request->all());

        if($request->has('is_ajax')) {
            return response()->json(['id'=> $categoria->id, 'nombre'=>$categoria->nombre]);
        }

        return redirect()->route('categorias.index')
        ->with('success', 'Categoria creada correctamente.');
    }

    public function show($id) {
        $categoria = Categoria::findOrFail($id);

        return view('categorias.show', compact('categoria'));
    }

    public function edit($id) {
        $categoria = Categoria::findOrFail($id);
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id) {
        $categoria = Categoria::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|unique:categoria,nombre,' . $categoria->id . '|max:255'
        ]);

        $categoria->update($request->all());

        return redirect()->route('categorias.index')
        ->with('success', 'La categoria fue actualizada correctamente.');
    }

    public function destroy(Categoria $categoria) {
        // No valido si esta siendo usado por algun movimiento ya que estos no se editan, solo se anulan. 
        $categoria->habilitado = 0;
        $categoria->save();

        return redirect()->route('categorias.index')
        ->with('succcess', 'Categoria fue dado de baja correctamente.');
    }
    
}
