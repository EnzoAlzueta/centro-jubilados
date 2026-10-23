<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $admin = DB::table('users')->where('email', 'admin@admin.com')->first();

        // Actualizar la clave inicial sin reemplazar claves personalizadas.
        if ($admin && Hash::check('enzoadmin', $admin->password)) {
            DB::table('users')->where('id', $admin->id)->update([
                'password' => Hash::make('scjadmin'),
                'remember_token' => null,
            ]);
        }
    }

    public function down(): void
    {
        // No restaurar una contraseña anterior al revertir la migración.
    }
};
