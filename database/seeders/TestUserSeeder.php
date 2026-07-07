<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TestUserSeeder extends Seeder
{
    /**
     * Crea (o actualiza) un usuario fijo para pruebas manuales.
     *
     * Es idempotente: puede ejecutarse las veces que sea sin duplicar datos.
     * Pensado para dejar siempre disponible un login en las compilaciones,
     * sin tener que crear un usuario nuevo en cada build.
     *
     *   php artisan db:seed --class=TestUserSeeder
     *
     * Credenciales:
     *   Email:      test@test.com
     *   Contraseña: test1234
     */
    public function run(): void
    {
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
}
