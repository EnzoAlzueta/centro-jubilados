<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InitialDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrations_create_default_login_and_catalog_data(): void
    {
        $user = User::where('email', 'test@test.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('Usuario de Prueba', $user->name);
        $this->assertTrue(Hash::check('test1234', $user->password));
        $this->assertTrue(Hash::check('tobi', $user->security_answer));
        $this->assertSame('¿Cuál es el nombre de la mascota del sistema?', $user->security_question);
        $admin = User::where('email', 'admin@admin.com')->firstOrFail();
        $this->assertTrue(Hash::check('enzoadmin', $admin->password));
        $this->assertDatabaseHas('barrios', ['nombre' => 'Centro']);
        $this->assertDatabaseHas('sectors', ['nombre' => 'Salón Principal', 'precio_base' => 150000]);
        $this->assertDatabaseHas('sectors', ['nombre' => 'Quincho c/ Parrilla', 'precio_base' => 80000]);
        $this->assertDatabaseHas('utilerias', ['nombre' => 'Silla Plástica Blanca', 'stock_total' => 150]);
        $this->assertDatabaseHas('utilerias', ['nombre' => 'Mesa Larga (8 personas)', 'stock_total' => 20]);
    }

    public function test_upgrade_restores_missing_user_without_overwriting_existing_data(): void
    {
        User::where('email', 'test@test.com')->delete();
        $admin = User::where('email', 'admin@admin.com')->firstOrFail();
        $admin->update(['name' => 'Administrador personalizado', 'password' => 'clave-personalizada']);
        $password = $admin->fresh()->password;

        $migration = require database_path('migrations/2026_10_07_000000_restore_initial_data.php');
        $migration->up();
        $migration->up();

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseCount('barrios', 1);
        $this->assertDatabaseCount('sectors', 2);
        $this->assertDatabaseCount('utilerias', 2);
        $this->assertTrue(Hash::check('test1234', User::where('email', 'test@test.com')->firstOrFail()->password));
        $this->assertSame('Administrador personalizado', $admin->fresh()->name);
        $this->assertSame($password, $admin->fresh()->password);

        $migration->down();
        $this->assertDatabaseCount('users', 2);
    }
}
