<?php

use App\Models\Barrio;
use App\Models\Sector;
use App\Models\Utileria;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

/**
 * Siembra los datos base (usuarios de acceso y catálogo inicial).
 *
 * ¿Por qué una migración y no un seeder?
 * En la app de escritorio (NativePHP) el cliente solo ejecuta `migrate --force`
 * al abrir; nunca corre `db:seed`. Poniendo la siembra en una migración se
 * garantiza que exista un usuario para iniciar sesión en cada instalación nueva.
 *
 * Es idempotente (firstOrCreate / updateOrCreate), así que también es segura
 * en el entorno de desarrollo aunque el DatabaseSeeder ya haya creado estos datos.
 */
return new class extends Migration
{
    public function up(): void
    {
        // --- Barrio base ---
        Barrio::firstOrCreate(['nombre' => 'Centro']);

        // --- Sectores (lugares para alquilar) ---
        Sector::firstOrCreate(
            ['nombre' => 'Salón Principal'],
            ['descripcion' => 'Capacidad para 200 personas con cocina', 'precio_base' => 150000.00]
        );
        Sector::firstOrCreate(
            ['nombre' => 'Quincho c/ Parrilla'],
            ['descripcion' => 'Capacidad para 50 personas', 'precio_base' => 80000.00]
        );

        // --- Utilería inicial ---
        Utileria::firstOrCreate(['nombre' => 'Silla Plástica Blanca'], ['stock_total' => 150]);
        Utileria::firstOrCreate(['nombre' => 'Mesa Larga (8 personas)'], ['stock_total' => 20]);

        // --- Usuario administrador ---
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Enzo Admin',
                'password' => bcrypt('enzoadmin'),
                'security_question' => '¿Cuál es el nombre de la mascota del sistema?',
                'security_answer' => bcrypt('tobi'),
            ]
        );

        // --- Usuario fijo para pruebas manuales ---
        User::updateOrCreate(
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
        User::whereIn('email', ['admin@admin.com', 'test@test.com'])->delete();
    }
};
