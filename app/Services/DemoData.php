<?php

namespace App\Services;

use App\Models\Alquiler;
use App\Models\Cuota;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class DemoData
{
    // Hijos primero: las referencias que siguen existiendo impiden borrar al padre.
    public const TABLES = [
        'movimientos' => 'Movimientos de caja', 'cuotas' => 'Cuotas',
        'alquileres' => 'Alquileres', 'socios' => 'Socios',
        'utilerias' => 'Utilería', 'sectors' => 'Sectores',
        'barrios' => 'Barrios', 'calles' => 'Calles', 'categoria' => 'Categorías',
    ];

    public static function trackCreated(Model $record): void
    {
        if ($record->wasRecentlyCreated && array_key_exists($record->getTable(), self::TABLES)) {
            DB::table('demo_records')->insertOrIgnore([
                'table_name' => $record->getTable(), 'record_id' => $record->getKey(),
            ]);
        }
    }

    public function counts(): array
    {
        $counts = [];
        foreach (self::TABLES as $table => $label) {
            $counts[$table] = DB::table($table)->whereIn('id', $this->ids($table))->count();
        }

        return $counts;
    }

    public function enable(): bool
    {
        return DB::transaction(function () {
            if (array_sum($this->counts()) > 0) {
                return false;
            }
            (new DemoDataSeeder)->run();

            return true;
        });
    }

    public function disable(): array
    {
        $backup = $this->backup();

        return DB::transaction(function () use ($backup) {
            $removing = [];
            foreach (self::TABLES as $table => $label) {
                $removing[$table] = DB::table($table)->whereIn('id', $this->ids($table))->pluck('id')->all();
                // Quitar marcas de registros que el usuario ya eliminó manualmente.
                DB::table('demo_records')->where('table_name', $table)
                    ->whereNotIn('record_id', $removing[$table])->delete();
            }
            $total = array_sum(array_map('count', $removing));
            // Resolver primero todos los vínculos. Si se conserva una cuota o
            // alquiler, también se conservan sus pagos para mantener la caja coherente.
            do {
                $changed = false;
                foreach ($removing as $table => $ids) {
                    foreach ($ids as $id) {
                        if ($this->hasReferences($table, $id, $removing)
                            || ($table === 'movimientos' && $this->paymentMustStay($id, $removing))) {
                            $removing[$table] = array_values(array_diff($removing[$table], [$id]));
                            $changed = true;
                        }
                    }
                }
            } while ($changed);

            $deleted = 0;
            foreach (self::TABLES as $table => $label) {
                if ($table === 'alquileres') {
                    DB::table('alquiler_utileria')->whereIn('alquiler_id', $removing[$table])->delete();
                }
                $deleted += DB::table($table)->whereIn('id', $removing[$table])->delete();
                DB::table('demo_records')->where('table_name', $table)
                    ->whereIn('record_id', $removing[$table])->delete();
            }
            $kept = $total - $deleted;

            return compact('deleted', 'kept', 'backup');
        });
    }

    private function ids(string $table): array
    {
        return DB::table('demo_records')->where('table_name', $table)->pluck('record_id')->all();
    }

    private function hasReferences(string $table, int $id, array $removing): bool
    {
        $references = match ($table) {
            'socios' => [['cuotas', 'socio_id'], ['alquileres', 'socio_id']],
            'sectors' => [['alquileres', 'sector_id']],

            'barrios' => [['socios', 'barrio_id']],
            'calles' => [['socios', 'calle_id']],
            'categoria' => [['movimientos', 'categoria_id']],
            default => [],
        };
        foreach ($references as [$child, $foreignKey]) {
            // Incluye cuotas anuladas (soft delete): también deben conservarse.
            if (DB::table($child)->where($foreignKey, $id)->whereNotIn('id', $removing[$child])->exists()) {
                return true;
            }
        }
        if ($table === 'utilerias' && DB::table('alquiler_utileria')->where('utileria_id', $id)
            ->whereNotIn('alquiler_id', $removing['alquileres'])->exists()) {
            return true;
        }
        $type = match ($table) {
            'cuotas' => (new Cuota)->getMorphClass(),
            'alquileres' => (new Alquiler)->getMorphClass(),
            default => null,
        };

        return $type !== null && DB::table('movimientos')
            ->where('referencia_type', $type)->where('referencia_id', $id)
            ->whereNotIn('id', $removing['movimientos'])->exists();
    }

    private function paymentMustStay(int $id, array $removing): bool
    {
        $payment = DB::table('movimientos')->where('id', $id)->first();
        $parent = match ($payment->referencia_type) {
            (new Cuota)->getMorphClass() => 'cuotas',
            (new Alquiler)->getMorphClass() => 'alquileres',
            default => null,
        };

        return $parent !== null && $payment->referencia_id !== null
            && ! in_array($payment->referencia_id, $removing[$parent]);
    }

    private function backup(): string
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            throw new RuntimeException('La limpieza requiere un respaldo SQLite.');
        }
        $disk = Storage::disk('local');
        $disk->makeDirectory('backups');
        $name = 'backups/antes-quitar-demo-'.now()->format('Ymd-His').'-'.Str::random(8).'.sqlite';
        // Snapshot consistente, incluso si SQLite está utilizando WAL.
        DB::statement('VACUUM INTO ?', [$disk->path($name)]);

        return $name;
    }
}
