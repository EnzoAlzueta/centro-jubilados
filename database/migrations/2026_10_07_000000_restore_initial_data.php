<?php

use App\Models\Barrio;
use App\Models\Sector;
use App\Models\User;
use App\Models\Utileria;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // NativePHP ejecuta migraciones, no seeders. Una migración nueva también
        // recupera instalaciones que ya registraron la siembra anterior vacía.
        Barrio::firstOrCreate(['nombre' => 'Centro']);

        Sector::firstOrCreate(
            ['nombre' => 'Salón Principal'],
            ['descripcion' => 'Capacidad para 200 personas con cocina', 'precio_base' => 150000.00]
        );
        Sector::firstOrCreate(
            ['nombre' => 'Quincho c/ Parrilla'],
            ['descripcion' => 'Capacidad para 50 personas', 'precio_base' => 80000.00]
        );

        Utileria::firstOrCreate(['nombre' => 'Silla Plástica Blanca'], ['stock_total' => 150]);
        Utileria::firstOrCreate(['nombre' => 'Mesa Larga (8 personas)'], ['stock_total' => 20]);

        // firstOrCreate conserva las credenciales de cuentas existentes.
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Enzo Admin',
                'password' => bcrypt('enzoadmin'),
                'security_question' => '¿Cuál es el nombre de la mascota del sistema?',
                'security_answer' => bcrypt('tobi'),
            ]
        );
        User::firstOrCreate(
            ['email' => 'test@test.com'],
            [
                'name' => 'Usuario de Prueba',
                'password' => bcrypt('test1234'),
                'security_question' => '¿Cuál es el nombre de la mascota del sistema?',
                'security_answer' => bcrypt('tobi'),
            ]
        );
    }

    public function down(): void
    {
        // Los registros pueden haber sido utilizados o modificados por el usuario.
        // No se eliminan cuentas ni datos de negocio al revertir esta reparación.
    }
};
