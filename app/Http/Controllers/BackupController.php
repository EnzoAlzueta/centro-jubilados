<?php

namespace App\Http\Controllers;

use App\Services\SqliteDatabaseTransfer;
use Native\Laravel\Dialog;

class BackupController extends Controller
{
    public function index()
    {
        return view('backups.index');
    }

    public function export(SqliteDatabaseTransfer $database)
    {
        try {
            $destination = Dialog::new()
                ->title('Exportar respaldo de Centro de Jubilados')
                ->defaultPath('centro-jubilados-respaldo-'.now()->format('Y-m-d_H-i-s').'.sqlite')
                ->button('Guardar respaldo')
                ->filter('Base de datos SQLite', ['sqlite'])
                ->save();

            if (! is_string($destination) || $destination === '') {
                return redirect()->route('backups.index')->with('info', 'Exportación cancelada.');
            }

            $path = $destination;
            if (! str_ends_with(strtolower($path), '.sqlite')) {
                $path .= '.sqlite';
            }

            $database->exportTo($path);

            return redirect()->route('backups.index')->with('success', "Respaldo guardado en: {$path}");
        } catch (\Exception $error) {
            report($error);

            return redirect()->route('backups.index')->with('error', 'No se pudo exportar la base. Elija una ubicación con espacio y permisos de escritura.');
        }
    }

    public function import(SqliteDatabaseTransfer $database)
    {
        try {
            $result = $database->importBackup();
            if ($result['canceled'] ?? false) {
                return redirect()->route('backups.index')->with('info', 'Importación cancelada. La base actual no se modificó.');
            }

            return redirect()->route('backups.index')->with('success', 'Respaldo importado. Se guardó una copia de seguridad previa en '.$result['backup'].'.');
        } catch (\Throwable $error) {
            report($error);

            return redirect()->route('backups.index')->with('error', 'No se pudo importar el respaldo. La base anterior se conservó o se restauró automáticamente. Detalle: '.$error->getMessage());
        }
    }
}
