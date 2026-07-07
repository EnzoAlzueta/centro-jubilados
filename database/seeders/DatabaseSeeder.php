<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barrio;
use App\Models\Socio;
use App\Models\Sector;
use App\Models\Utileria;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Creamos el Barrio base
        $barrio = Barrio::create([
            'nombre' => 'Centro',
        ]);

        // 2. Creamos los Sectores (Lugares para alquilar)
        Sector::create([
            'nombre' => 'Salón Principal',
            'descripcion' => 'Capacidad para 200 personas con cocina',
            'precio_base' => 150000.00
        ]);

        Sector::create([
            'nombre' => 'Quincho c/ Parrilla',
            'descripcion' => 'Capacidad para 50 personas',
            'precio_base' => 80000.00
        ]);

        // 3. Creamos la Utilería inicial
        Utileria::create([
            'nombre' => 'Silla Plástica Blanca',
            'stock_total' => 150
        ]);

        Utileria::create([
            'nombre' => 'Mesa Larga (8 personas)',
            'stock_total' => 20
        ]);

        // 4. Creamos los Socios de prueba vinculados al barrio
        Socio::factory(10)->create([
            'barrio_id' => $barrio->id
        ]);

        // 5. Usuario para el sistema
        User::factory()->create([
            'name' => 'Enzo Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('enzoadmin'),
            'security_question' => '¿Cuál es el nombre de la mascota del sistema?',
            'security_answer' => bcrypt('tobi'),
        ]);

        // 6. Usuario fijo para pruebas manuales (idempotente)
        $this->call(TestUserSeeder::class);
    }
}