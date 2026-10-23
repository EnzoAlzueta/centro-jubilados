<?php

namespace Database\Seeders;

use App\Models\Alquiler;
use App\Models\Barrio;
use App\Models\Calle;
use App\Models\Categoria;
use App\Models\Cuota;
use App\Models\Movimiento;
use App\Models\Sector;
use App\Models\Socio;
use App\Models\User;
use App\Models\Utileria;
use App\Services\DemoData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            User::firstOrCreate(['email' => 'test@test.com'], [
                'name' => 'Usuario de Prueba', 'password' => Hash::make('test1234'),
                'security_question' => '¿Cuál es el nombre de la mascota del sistema?',
                'security_answer' => Hash::make('tobi'),
            ]);
            $barrio = Barrio::firstOrCreate(['nombre' => 'Barrio DEMO']);
            DemoData::trackCreated($barrio);
            $calle = Calle::firstOrCreate(['nombre' => 'Calle DEMO']);
            DemoData::trackCreated($calle);
            $salon = Sector::firstOrCreate(['nombre' => 'Salón DEMO'], [
                'descripcion' => 'Espacio ficticio para pruebas', 'precio_base' => 100000,
            ]);
            DemoData::trackCreated($salon);
            $quincho = Sector::firstOrCreate(['nombre' => 'Quincho DEMO'], [
                'descripcion' => 'Espacio ficticio para pruebas', 'precio_base' => 60000,
            ]);
            DemoData::trackCreated($quincho);
            $sillas = Utileria::firstOrCreate(['nombre' => 'Sillas DEMO'], ['stock_total' => 100]);
            DemoData::trackCreated($sillas);
            $mesas = Utileria::firstOrCreate(['nombre' => 'Mesas DEMO'], ['stock_total' => 20]);
            DemoData::trackCreated($mesas);
            $categorias = [];
            foreach (['Alquiler', 'Cuota', 'Mantenimiento', 'Insumos'] as $nombre) {
                $categorias[$nombre] = Categoria::firstOrCreate(['nombre' => $nombre]);
                DemoData::trackCreated($categorias[$nombre]);
            }

            $socios = collect();
            foreach (['Ana', 'Carlos', 'Marta', 'José', 'Elena', 'Roberto'] as $i => $nombre) {
                $socio = Socio::firstOrCreate(['email' => 'demo.socio'.($i + 1).'@example.com'], [
                    'numero_socio' => (string) (990001 + $i), 'dni' => (string) (90000001 + $i),
                    'nombre' => $nombre, 'apellido' => 'DEMO '.($i + 1),
                    'barrio_id' => $barrio->id, 'calle_id' => $calle->id, 'altura' => 100 + $i,
                    'telefono' => null, 'fecha_nacimiento' => '1955-05-15',
                    'fecha_alta' => now()->subYear()->toDateString(), 'habilitado' => $i !== 5,
                ]);
                DemoData::trackCreated($socio);
                $socios->push($socio);
                for ($atras = 0; $atras < 3; $atras++) {
                    $periodo = now()->startOfMonth()->subMonths($atras);
                    $pagada = $i < 3 || ($i === 3 && $atras > 0);
                    $cuota = Cuota::withTrashed()->firstOrCreate([
                        'socio_id' => $socio->id, 'mes' => $periodo->month, 'anio' => $periodo->year,
                    ], [
                        'monto' => 5000, 'pagado' => $pagada,
                        'fecha_pago' => $pagada ? $periodo->toDateString() : null,
                    ]);
                    DemoData::trackCreated($cuota);
                    if ($cuota->pagado && ! $cuota->trashed()) {
                        $movimiento = Movimiento::firstOrCreate([
                            'referencia_type' => Cuota::class, 'referencia_id' => $cuota->id,
                        ], [
                            'fecha' => $cuota->fecha_pago, 'tipo' => 'ingreso',
                            'concepto' => 'DEMO: cuota de '.$socio->nombre.' '.$socio->apellido,
                            'monto' => $cuota->monto, 'categoria_id' => $categorias['Cuota']->id,
                        ]);
                        DemoData::trackCreated($movimiento);
                    }
                }
            }

            foreach (['reservado', 'reservado', 'confirmado', 'cancelado', 'finalizado'] as $i => $estado) {
                $sector = $i % 2 === 0 ? $salon : $quincho;
                $fecha = $estado === 'finalizado' ? now()->startOfMonth() : now()->addDays($i + 1);
                $pagado = match ($i) {
                    0, 3 => 0, 1 => 20000, default => $sector->precio_base
                };
                $alquiler = Alquiler::firstOrCreate(['tipo_evento' => 'DEMO: Evento '.($i + 1)], [
                    'sector_id' => $sector->id, 'socio_id' => $i % 2 === 0 ? $socios[$i]->id : null,
                    'solicitante_externo' => $i % 2 !== 0 ? 'Solicitante DEMO '.($i + 1) : null,
                    'dni_solicitante_externo' => $i % 2 !== 0 ? (string) (91000001 + $i) : null,
                    'fecha_evento' => $fecha->setTime(18, 0), 'precio' => $sector->precio_base,
                    'seña_pagada' => $pagado, 'estado' => $estado,
                ]);
                DemoData::trackCreated($alquiler);
                if ($alquiler->wasRecentlyCreated) {
                    $alquiler->utilerias()->attach([$sillas->id => ['cantidad' => 20], $mesas->id => ['cantidad' => 4]]);
                    if ($pagado > 0) {
                        $movimiento = Movimiento::create([
                            'referencia_type' => Alquiler::class, 'referencia_id' => $alquiler->id,
                            'fecha' => now()->toDateString(), 'tipo' => 'ingreso',
                            'concepto' => 'DEMO: cobro de '.$alquiler->tipo_evento,
                            'monto' => $pagado, 'categoria_id' => $categorias['Alquiler']->id,
                        ]);
                        DemoData::trackCreated($movimiento);
                    }
                }
            }
            foreach (['Mantenimiento' => 12000, 'Insumos' => 3500] as $nombre => $monto) {
                $movimiento = Movimiento::firstOrCreate(['concepto' => 'DEMO: gasto de '.$nombre], [
                    'fecha' => now()->toDateString(), 'tipo' => 'egreso', 'monto' => $monto,
                    'categoria_id' => $categorias[$nombre]->id,
                ]);
                DemoData::trackCreated($movimiento);
            }
        });
    }
}
