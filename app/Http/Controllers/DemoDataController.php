<?php

namespace App\Http\Controllers;

use App\Services\DemoData;
use Illuminate\Http\Request;

class DemoDataController extends Controller
{
    public function index(DemoData $demo)
    {
        return view('demo.index', ['counts' => $demo->counts(), 'labels' => DemoData::TABLES]);
    }

    public function store(DemoData $demo)
    {
        try {
            $created = $demo->enable();

            return redirect()->route('demo.index')->with(
                $created ? 'success' : 'warning',
                $created ? 'Datos de prueba habilitados. Ya están disponibles para todos los usuarios.'
                    : 'Ya hay datos de prueba cargados. No se agregaron duplicados.'
            );
        } catch (\Exception $error) {
            report($error);

            return redirect()->route('demo.index')->with('error', 'No se pudieron cargar los datos de prueba. No se realizaron cambios.');
        }
    }

    public function destroy(Request $request, DemoData $demo)
    {
        $request->validate(['confirmar' => ['required', 'accepted']]);
        if (array_sum($demo->counts()) === 0) {
            return redirect()->route('demo.index')->with('warning', 'No hay datos de prueba registrados para quitar.');
        }
        try {
            $result = $demo->disable();
            $message = "Se quitaron {$result['deleted']} registros de prueba. Se guardó un respaldo antes de la limpieza.";
            if ($result['kept'] > 0) {
                return redirect()->route('demo.index')->with('warning', $message." Se conservaron {$result['kept']} registros vinculados a datos ajenos a la carga DEMO. Revise sus relaciones antes de volver a intentar la limpieza.");
            }

            return redirect()->route('demo.index')->with('success', $message);
        } catch (\Exception $error) {
            report($error);

            return redirect()->route('demo.index')->with('error', 'No se pudieron quitar los datos de prueba. No se realizaron cambios. Compruebe que haya espacio y permisos para guardar el respaldo.');
        }
    }
}
