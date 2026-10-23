#!/usr/bin/env python3
"""Compila una edición DEMO aislada y restaura la configuración local al terminar."""
import os
from pathlib import Path
import re
import subprocess

root = Path(__file__).resolve().parent.parent
env_path = root / '.env'
migration = root / 'database/migrations/2026_10_23_010000_load_demo_data.php'
if migration.exists():
    raise SystemExit('Ya existe la migración temporal DEMO; revisar antes de compilar.')
original = env_path.read_bytes()
values = {
    'APP_NAME': 'Centro-Jubilados-DEMO',
    'APP_ENV': 'production',
    'APP_DEBUG': 'false',
    'NATIVEPHP_APP_ID': 'com.centrojubilados.demo',
    'NATIVEPHP_APP_VERSION': '1.1.5',
    'NATIVEPHP_UPDATER_ENABLED': 'false',
}
try:
    contents = original.decode()
    for key, value in values.items():
        line = f'{key}={value}'
        if re.search(rf'^{key}=.*$', contents, re.M):
            contents = re.sub(rf'^{key}=.*$', lambda _: line, contents, flags=re.M)
        else:
            contents += '\n' + line + '\n'
    env_path.write_text(contents)
    migration.write_text('''<?php

use Illuminate\\Database\\Migrations\\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (config('app.name') === 'Centro-Jubilados-DEMO') {
            (new \\Database\\Seeders\\DemoDataSeeder)->run();
        }
    }

    public function down(): void
    {
        // Conservar las pruebas que el usuario haya modificado.
    }
};
''')
    build_env = os.environ.copy()
    build_env.update(values)
    subprocess.run(['php', 'artisan', 'native:build', 'win', 'x64', '--no-interaction'],
                   cwd=root, env=build_env, check=True)
finally:
    env_path.write_bytes(original)
    migration.unlink(missing_ok=True)
