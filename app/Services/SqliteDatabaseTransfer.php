<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Native\Laravel\Dialog;
use Native\Laravel\Facades\QueueWorker;
use RuntimeException;
use SQLite3;

class SqliteDatabaseTransfer
{
    public function exportTo(string $destination): string
    {
        $this->ensureSqlite();
        $disk = Storage::disk('local');
        $disk->makeDirectory('backups');
        $temporary = $disk->path('backups/exportar-'.Str::random(24).'.sqlite');

        try {
            DB::statement('VACUUM INTO ?', [$temporary]);
            File::copy($temporary, $destination);

            return $destination;
        } finally {
            if (File::exists($temporary)) {
                File::delete($temporary);
            }
        }
    }

    public function importBackup(): array
    {
        $this->ensureSqlite();
        $source = Dialog::new()
            ->title('Importar respaldo de Centro de Jubilados')
            ->button('Seleccionar respaldo')
            ->filter('Base de datos SQLite', ['sqlite'])
            ->open();

        if (! is_string($source) || $source === '') {
            return ['imported' => false, 'canceled' => true];
        }

        return ['imported' => true] + $this->restoreFrom($source);
    }

    public function restoreFrom(string $source): array
    {
        $this->ensureSqlite();

        $active = DB::connection()->getDatabaseName();
        $sourcePath = realpath($source);
        if ($sourcePath === false || ! is_file($sourcePath) || ! is_readable($sourcePath)) {
            throw new RuntimeException('El archivo seleccionado no se puede leer.');
        }
        if ($sourcePath === realpath($active)) {
            throw new RuntimeException('Elige un archivo de respaldo distinto de la base que está en uso.');
        }

        $this->validateBackup($sourcePath);
        $backup = $this->createSafetyBackup();
        $staging = dirname($active).DIRECTORY_SEPARATOR.'.'.basename($active).'.import-'.Str::random(16).'.sqlite';
        $previous = $active.'.before-import-'.Str::random(12);
        $originalMoved = false;
        $newInstalled = false;
        $stoppedQueueWorkers = [];

        try {
            File::copy($sourcePath, $staging);
            $this->validateBackup($staging);

            if (config('nativephp-internal.running')) {
                foreach (array_keys(config('nativephp.queue_workers', [])) as $alias) {
                    QueueWorker::down($alias);
                    $stoppedQueueWorkers[] = $alias;
                }
            }

            $this->closeDatabaseAndRemoveSidecars($active);
            DB::purge();

            if (! rename($active, $previous)) {
                throw new RuntimeException('No se pudo preparar la base actual para reemplazarla.');
            }
            $originalMoved = true;
            if (! rename($staging, $active)) {
                throw new RuntimeException('No se pudo instalar el respaldo seleccionado.');
            }
            $newInstalled = true;

            DB::purge();
            if (Artisan::call('migrate', ['--force' => true]) !== 0) {
                throw new RuntimeException('No se pudieron aplicar las migraciones pendientes del respaldo.');
            }
            $this->validateBackup($active, requireCurrentSchema: true);
            $this->closeDatabaseAndRemoveSidecars($active);
            File::delete($previous);

            return ['backup' => basename($backup)];
        } catch (\Throwable $error) {
            try {
                DB::purge();
                if ($newInstalled && File::exists($active) && ! File::delete($active)) {
                    throw new RuntimeException('No se pudo retirar la base importada durante la recuperación.');
                }
                $this->removeDatabaseSidecars($active);
                if ($originalMoved && File::exists($previous) && ! rename($previous, $active)) {
                    throw new RuntimeException('No se pudo restaurar el archivo anterior.');
                }
                DB::purge();
            } catch (\Throwable $rollbackError) {
                throw new RuntimeException('Falló la importación y también la recuperación automática. El respaldo de seguridad está en '.$backup, previous: $rollbackError);
            }

            throw $error;
        } finally {
            foreach ($stoppedQueueWorkers as $alias) {
                try {
                    QueueWorker::up($alias);
                } catch (\Throwable $error) {
                    report($error);
                }
            }

            if (File::exists($staging)) {
                File::delete($staging);
            }
        }
    }

    private function ensureSqlite(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            throw new RuntimeException('Esta operación requiere una base de datos SQLite.');
        }
    }

    private function closeDatabaseAndRemoveSidecars(string $path): void
    {
        $journalMode = strtolower((string) DB::scalar('PRAGMA journal_mode'));
        if ($journalMode === 'wal') {
            DB::select('PRAGMA wal_checkpoint(TRUNCATE)');
        }

        DB::purge();
        $this->removeDatabaseSidecars($path);
    }

    private function removeDatabaseSidecars(string $path): void
    {
        foreach ([$path.'-wal', $path.'-shm'] as $sidecar) {
            if (File::exists($sidecar) && ! File::delete($sidecar)) {
                throw new RuntimeException('No se pudieron cerrar los archivos auxiliares de SQLite. Cierra la aplicación y vuelve a intentarlo.');
            }
        }
    }

    private function validateBackup(string $path, bool $requireCurrentSchema = false): void
    {
        $database = new SQLite3($path, SQLITE3_OPEN_READONLY);
        try {
            $integrity = $database->query('PRAGMA integrity_check');
            while ($row = $integrity->fetchArray(SQLITE3_NUM)) {
                if ($row[0] !== 'ok') {
                    throw new RuntimeException('El respaldo SQLite está dañado.');
                }
            }
            $foreignKeys = $database->query('PRAGMA foreign_key_check');
            if ($foreignKeys->fetchArray(SQLITE3_NUM) !== false) {
                throw new RuntimeException('El respaldo contiene relaciones incompletas.');
            }

            $tables = [];
            $query = $database->query("SELECT name FROM sqlite_master WHERE type = 'table'");
            while ($row = $query->fetchArray(SQLITE3_NUM)) {
                $tables[] = $row[0];
            }
            foreach (['migrations', 'users', 'socios', 'cuotas', 'alquileres', 'movimientos', 'sectors', 'utilerias', 'barrios', 'calles', 'categoria', 'alquiler_utileria'] as $required) {
                if (! in_array($required, $tables, true)) {
                    throw new RuntimeException('El archivo seleccionado no es una base de Centro de Jubilados válida.');
                }
            }

            $columns = [];
            $query = $database->query('PRAGMA table_info(users)');
            while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
                $columns[] = $row['name'];
            }
            if (! in_array('email', $columns, true) || ! in_array('password', $columns, true)) {
                throw new RuntimeException('La base seleccionada no contiene cuentas compatibles.');
            }
            if ($requireCurrentSchema && ! in_array('is_admin', $columns, true)) {
                throw new RuntimeException('La base importada no tiene permisos de administrador compatibles.');
            }

            $users = $database->querySingle('SELECT COUNT(*) FROM users');
            $admins = in_array('is_admin', $columns, true)
                ? $database->querySingle('SELECT COUNT(*) FROM users WHERE is_admin = 1')
                : $database->querySingle("SELECT COUNT(*) FROM users WHERE email = 'admin@admin.com'");
            if ($users < 1 || $admins < 1) {
                throw new RuntimeException('La base seleccionada no contiene una cuenta administradora.');
            }
        } finally {
            $database->close();
        }
    }

    private function createSafetyBackup(): string
    {
        $disk = Storage::disk('local');
        $disk->makeDirectory('backups');
        $name = 'backups/antes-importar-'.now()->format('Ymd-His').'-'.Str::random(8).'.sqlite';
        DB::statement('VACUUM INTO ?', [$disk->path($name)]);

        return $name;
    }
}
