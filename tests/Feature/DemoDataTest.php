<?php

namespace Tests\Feature;

use App\Models\{Alquiler, Movimiento, Socio, User};
use Database\Seeders\DemoDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_test_user_can_generate_reports_with_demo_data(): void
    {
        $this->seed(DemoDataSeeder::class);
        $this->actingAs(User::where('email', 'test@test.com')->firstOrFail());
        foreach (['reportes.socios', 'reportes.alquileres', 'reportes.caja'] as $route) {
            $this->get(route($route))->assertOk()->assertHeader('content-type', 'application/pdf');
        }
    }

    public function test_demo_data_is_consistent_and_can_be_reloaded_without_overwriting_changes(): void
    {
        $password = User::where('email', 'test@test.com')->value('password');
        $this->seed(DemoDataSeeder::class);
        $this->assertDatabaseCount('socios', 6);
        $this->assertDatabaseCount('cuotas', 18);
        $this->assertDatabaseCount('alquileres', 5);
        $this->assertDatabaseCount('movimientos', 16);
        $this->assertSame(0, Movimiento::whereNull('categoria_id')->count());
        $this->assertSame(0, Alquiler::doesntHave('utilerias')->count());
        $this->assertDatabaseHas('cuotas', ['pagado' => false]);
        $this->assertDatabaseHas('socios', ['habilitado' => false]);
        $socio = Socio::firstOrFail();
        $socio->update(['nombre' => 'Nombre editado']);
        $this->seed(DemoDataSeeder::class);
        $this->assertDatabaseCount('socios', 6);
        $this->assertDatabaseCount('cuotas', 18);
        $this->assertDatabaseCount('alquileres', 5);
        $this->assertDatabaseCount('movimientos', 16);
        $this->assertSame('Nombre editado', $socio->fresh()->nombre);
        $this->assertSame($password, User::where('email', 'test@test.com')->value('password'));
        $this->assertFalse(User::where('email', 'test@test.com')->firstOrFail()->is_admin);
    }
}
