<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use App\Models\Alquiler;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReporteController extends Controller
{
    /**
     * Mostrar la vista principal de reportes.
     */
    public function index()
    {
        return view('reportes.index');
    }

    /**
     * Generar PDF con el listado de socios.
     */
    public function sociosPdf(Request $request)
    {
        $socios = Socio::with('barrio')->orderBy('apellido')->get();

        $mensajeVacio = 'No hay socios registrados: no hay datos disponibles para generar este reporte.';

        // Pre-chequeo AJAX: la vista consulta primero (?check=1) si hay datos.
        // Si no hay, muestra el mensaje en la misma ventana sin abrir el preview.
        if ($request->boolean('check')) {
            return response()->json([
                'has_data' => $socios->isNotEmpty(),
                'message' => $mensajeVacio,
            ]);
        }

        if ($socios->isEmpty()) {
            return redirect()->route('reportes.index')->with('error', $mensajeVacio);
        }

        $fecha = Carbon::now()->format('d/m/Y');

        $pdf = Pdf::loadView('reportes.pdf.socios', compact('socios', 'fecha'));
        // stream() envía el PDF inline (Content-Disposition: inline) para mostrarlo
        // como vista previa en el visor del navegador, desde donde se puede imprimir o guardar.
        return $pdf->stream('reporte_socios_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Generar PDF con el listado de alquileres.
     */
    public function alquileresPdf(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);

        $alquileres = Alquiler::with(['sector', 'socio'])
            ->whereMonth('fecha_evento', $mes)
            ->whereYear('fecha_evento', $anio)
            ->orderBy('fecha_evento', 'asc')
            ->get();

        $nombreMes = $this->getNombreMes($mes);
        $mensajeVacio = "No hay alquileres registrados para {$nombreMes} de {$anio}: no hay datos disponibles para generar este reporte.";

        if ($request->boolean('check')) {
            return response()->json([
                'has_data' => $alquileres->isNotEmpty(),
                'message' => $mensajeVacio,
            ]);
        }

        if ($alquileres->isEmpty()) {
            return redirect()->route('reportes.index')->with('error', $mensajeVacio);
        }

        $fecha = Carbon::now()->format('d/m/Y');

        $pdf = Pdf::loadView('reportes.pdf.alquileres', compact('alquileres', 'fecha', 'mes', 'anio', 'nombreMes'));
        return $pdf->stream("reporte_alquileres_{$nombreMes}_{$anio}.pdf");
    }

    /**
     * Generar PDF detallado de movimientos de caja.
     */
    public function cajaPdf(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $anio = $request->get('anio', now()->year);

        $movimientos = Movimiento::whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->orderBy('fecha', 'asc')
            ->get();

        $nombreMes = $this->getNombreMes($mes);
        $mensajeVacio = "No hay movimientos de caja para {$nombreMes} de {$anio}: no hay datos disponibles para generar este reporte.";

        if ($request->boolean('check')) {
            return response()->json([
                'has_data' => $movimientos->isNotEmpty(),
                'message' => $mensajeVacio,
            ]);
        }

        if ($movimientos->isEmpty()) {
            return redirect()->route('reportes.index')->with('error', $mensajeVacio);
        }

        $totalIngresos = Movimiento::whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->where('tipo', 'ingreso')
            ->sum('monto');

        $totalEgresos = Movimiento::whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->where('tipo', 'egreso')
            ->sum('monto');

        $saldo = $totalIngresos - $totalEgresos;
        $fecha = Carbon::now()->format('d/m/Y');

        $pdf = Pdf::loadView('reportes.pdf.caja', compact(
            'movimientos',
            'totalIngresos',
            'totalEgresos',
            'saldo',
            'fecha',
            'mes',
            'anio',
            'nombreMes'
        ));

        return $pdf->stream("reporte_caja_{$nombreMes}_{$anio}.pdf");
    }

    /**
     * Helper para obtener el nombre del mes en español.
     */
    private function getNombreMes($mes)
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        return $meses[(int)$mes];
    }
}