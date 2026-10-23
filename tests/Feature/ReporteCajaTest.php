<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Movimiento;
use Barryvdh\DomPDF\Facade\Pdf;
use Tests\TestCase;

class ReporteCajaTest extends TestCase
{
    public function test_pdf_accepts_rental_payments_without_category(): void
    {
        $sinCategoria = new Movimiento([
            'fecha' => '2026-10-23', 'tipo' => 'ingreso',
            'concepto' => 'Seña Alquiler: Prueba', 'monto' => 1000,
        ]);
        $sinCategoria->setRelation('categoria', null);
        $conCategoria = new Movimiento([
            'fecha' => '2026-10-23', 'tipo' => 'ingreso',
            'concepto' => 'Pago Saldo Alquiler: Prueba', 'monto' => 2000,
        ]);
        $conCategoria->setRelation('categoria', new Categoria(['nombre' => 'Alquiler']));

        $datos = [
            'movimientos' => collect([$sinCategoria, $conCategoria]),
            'totalIngresos' => 3000, 'totalEgresos' => 0, 'saldo' => 3000,
            'fecha' => '23/10/2026', 'nombreMes' => 'Octubre', 'anio' => 2026,
        ];
        $html = view('reportes.pdf.caja', $datos)->render();
        $this->assertStringContainsString('Sin categoría', $html);
        $this->assertStringContainsString('Alquiler', $html);
        $this->assertStringContainsString('3,000.00', $html);
        $this->assertStringStartsWith('%PDF-', Pdf::loadHTML($html)->output());
    }
}
