<?php

namespace Tests\Feature;

use App\Models\Alquiler;
use App\Models\Movimiento;
use App\Models\Socio;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DemoManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // VACUUM requiere una conexión sin la transacción de RefreshDatabase.
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        $this->artisan('migrate', ['--force' => true]);
        Storage::fake('local');
    }

    public function test_only_admin_can_manage_demo_data(): void
    {
        $this->get('/datos-prueba')->assertRedirect('/login');
        $this->post('/datos-prueba')->assertRedirect('/login');
        $this->delete('/datos-prueba', ['confirmar' => '1'])->assertRedirect('/login');
        $this->actingAs(User::where('email', 'test@test.com')->firstOrFail());
        $this->get('/datos-prueba')->assertForbidden();
        $this->post('/datos-prueba')->assertForbidden();
        $this->delete('/datos-prueba', ['confirmar' => '1'])->assertForbidden();
    }

    public function test_admin_can_load_and_remove_demo_data_with_a_backup_and_without_duplicates(): void
    {
        $this->actingAs(User::where('email', 'admin@admin.com')->firstOrFail());
        $this->get('/datos-prueba')->assertOk()->assertSee('Habilitar datos de prueba');
        $this->post('/datos-prueba')->assertRedirect('/datos-prueba');
        Socio::firstOrFail()->update(['nombre' => 'Editado', 'email' => 'editado@example.com']);
        // Rehabilitar desde el panel no debe repetir ni recrear ejemplos editados.
        $this->post('/datos-prueba')->assertRedirect('/datos-prueba');
        $this->assertDatabaseCount('socios', 6);
        $this->assertDatabaseCount('alquileres', 5);
        $this->assertDatabaseCount('movimientos', 16);
        $this->delete('/datos-prueba')->assertSessionHasErrors('confirmar');
        $this->assertDatabaseCount('socios', 6);
        $this->delete('/datos-prueba', ['confirmar' => '1'])->assertRedirect('/datos-prueba')->assertSessionHas('success');
        $this->assertDatabaseCount('socios', 0);
        $this->assertDatabaseCount('cuotas', 0);
        $this->assertDatabaseCount('alquileres', 0);
        $this->assertDatabaseCount('movimientos', 0);
        $this->assertDatabaseCount('users', 2);
        $files = Storage::disk('local')->files('backups');
        $this->assertCount(1, $files);
        $backup = new \SQLite3(Storage::disk('local')->path($files[0]), SQLITE3_OPEN_READONLY);
        $this->assertSame(6, $backup->querySingle('SELECT COUNT(*) FROM socios'));
        $backup->close();
        $this->post('/datos-prueba')->assertRedirect('/datos-prueba');
        $this->assertDatabaseCount('socios', 6);
    }

    public function test_backup_failure_prevents_deletion(): void
    {
        $this->actingAs(User::where('email', 'admin@admin.com')->firstOrFail());
        $this->post('/datos-prueba');
        Storage::shouldReceive('disk')->with('local')->andThrow(new \RuntimeException('Sin espacio'));
        $this->delete('/datos-prueba', ['confirmar' => '1'])->assertSessionHas('error');
        $this->assertDatabaseCount('socios', 6);
        $this->assertDatabaseCount('cuotas', 18);
        $this->assertDatabaseCount('alquileres', 5);
        $this->assertDatabaseCount('movimientos', 16);
    }

    public function test_cleanup_preserves_untracked_records_and_their_demo_dependencies(): void
    {
        $this->actingAs(User::where('email', 'admin@admin.com')->firstOrFail());
        $this->post('/datos-prueba');
        $socio = Socio::firstOrFail();
        $alquilerDemo = Alquiler::firstOrFail();
        $real = Alquiler::create([
            'sector_id' => $alquilerDemo->sector_id, 'socio_id' => $socio->id,
            'fecha_evento' => now()->addDays(15), 'tipo_evento' => 'Evento real',
            'precio' => 50000, 'seña_pagada' => 0, 'estado' => 'reservado',
        ]);
        $real->utilerias()->attach($alquilerDemo->utilerias->first()->id, ['cantidad' => 1]);
        $movimientoReal = Movimiento::create([
            'fecha' => now(), 'tipo' => 'ingreso', 'concepto' => 'DEMO escrito por usuario: conservar',
            'monto' => 1000, 'categoria_id' => Movimiento::firstOrFail()->categoria_id,
        ]);
        $cuota = DB::table('cuotas')->where('socio_id', $socio->id)->first();
        DB::table('cuotas')->where('id', $cuota->id)->update(['deleted_at' => now()]);
        $movimientoVinculado = Movimiento::create([
            'fecha' => now(), 'tipo' => 'ingreso', 'concepto' => 'Referencia real a cuota DEMO',
            'monto' => 100, 'referencia_type' => \App\Models\Cuota::class, 'referencia_id' => $cuota->id,
        ]);
        $pagoDemo = Movimiento::where('referencia_type', \App\Models\Cuota::class)->where('referencia_id', $cuota->id)->firstOrFail();
        $this->delete('/datos-prueba', ['confirmar' => '1'])->assertSessionHas('warning');
        $this->assertDatabaseHas('movimientos', ['id' => $pagoDemo->id]);
        $this->assertDatabaseHas('alquileres', ['id' => $real->id]);
        $this->assertDatabaseHas('movimientos', ['id' => $movimientoReal->id]);
        $this->assertDatabaseHas('movimientos', ['id' => $movimientoVinculado->id]);
        $this->assertDatabaseHas('cuotas', ['id' => $cuota->id]);
        $this->assertDatabaseHas('socios', ['id' => $socio->id]);
        $this->assertNotNull($real->fresh()->sector);
        $this->assertCount(1, $real->fresh()->utilerias);
        $this->assertDatabaseCount('users', 2);
    }
}
