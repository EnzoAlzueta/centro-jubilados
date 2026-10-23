# Sistema de Gestión - Centro de Jubilados

Este es un sistema integral de gestión diseñado para administrar las operaciones diarias de un Centro de Jubilados. Permite gestionar socios, barrios, alquileres de sectores y utilería, movimientos de caja y generación de reportes.

## 🚀 Módulos Principales

-   **Socios**: Registro y administración de socios con vinculación a barrios.
-   **Alquileres**: Gestión de reservas de sectores (salones, quinchos) y utilería (sillas, mesas), con validación de stock en tiempo real.
-   **Caja**: Seguimiento de ingresos y egresos, incluyendo el pago de cuotas sociales.
-   **Reportes**: Generación de documentos PDF para socios, alquileres y movimientos de caja.
-   **Barrios**: Administración de zonas geográficas asociadas a los socios.

## 🛠️ Tecnologías

-   **Framework**: [Laravel 12](https://laravel.com)
-   **Lenguaje**: PHP 8.2+
-   **Frontend**: Tailwind CSS, Boostrap & Blade
-   **Bundler**: Vite
-   **Base de Datos**: SQLite (por defecto)

## 💻 Puesta en Marcha

Para poner en funcionamiento el proyecto localmente, seguí estos pasos:

1.  **Clonar el repositorio**:
    ```bash
    git clone <url-del-repositorio>
    cd centro-jubilados
    ```

2.  **Ejecutar el Setup Automático**:
    El proyecto cuenta con un comando que automatiza la instalación de dependencias, configuración de entorno y migraciones:
    ```bash
    composer setup
    ```
    *Este comando instalará dependencias de PHP y JS, creará el archivo `.env`, generará la clave de aplicación y ejecutará las migraciones.*

3.  **Poblar la base de datos (Seeders)**:
    Para contar con datos de prueba (incluyendo el usuario administrador):
    ```bash
    php artisan db:seed
    ```

4.  **Iniciar el servidor de desarrollo**:
    ```bash
    composer dev
    ```
    *Este comando inicia simultáneamente el servidor de Laravel y el compilador de Vite.*

## 🔐 Credenciales de Acceso (Admin)

-   **Usuario**: `admin@admin.com`
-   **Contraseña**: `scjadmin`

## 🔄 ¿Qué hacer luego de un `git pull`?

Para asegurar que tu entorno local esté sincronizado con los últimos cambios del repositorio, se recomienda ejecutar:

NOTA: Anteponer "./vendor/bin/sail" a los comandos.

```bash
composer setup
php artisan db:seed # Si hay nuevos seeders necesarios
```

O de forma manual:
1.  `composer install` - Si hubo cambios en `composer.json`.
2.  `npm install` - Si hubo cambios en `package.json`.
3.  `php artisan migrate` - Si hay nuevas migraciones.
4.  `npm run build` ó `npm run dev` - Para actualizar los assets.
5.  `php artisan route:clear` - Para limpiar la cache de rutas.
6.  `php artisan view:clear` - Para limpiar la cache de vistas.

## 📦 Distribución como Aplicación de Escritorio (Windows)

El sistema puede empaquetarse como una aplicación nativa de Windows (.exe) utilizando NativePHP. Esto permite que el cliente lo use sin instalar Docker, PHP o servidores externos.

### Requisitos previos para compilar (en Linux)

Para generar el instalador de Windows desde un entorno Linux (como Ubuntu), es necesario contar con:

#### Wine (con soporte de 32 bits):

```bash
sudo dpkg --add-architecture i386
sudo apt update
sudo apt install wine wine32
wineboot -u
```

#### Pasos para generar el instalador .exe

Para asegurar una compilación limpia y funcional, seguí este orden estrictamente:

##### Preparar la Base de Datos:
Asegurate de que el archivo .env apunte a SQLite:

`DB_CONNECTION=sqlite`

> **⚠️ Importante — La base NO se empaqueta.** En la app instalada, NativePHP crea una base SQLite **vacía** en la carpeta de datos del usuario (`%APPDATA%`) y solo ejecuta `migrate --force` al abrir; **nunca** corre `db:seed`. Por eso los datos base (usuario para iniciar sesión, sectores, utilería) se siembran mediante una **migración idempotente** (`database/migrations/*_seed_initial_data.php`), que sí corre como parte del `migrate`. Si agregás datos que deban existir en cada instalación, ponelos ahí (con `firstOrCreate` / `updateOrCreate`), no en un seeder.

##### Nombre del Instalador:
El nombre del `.exe` sale de `APP_NAME` (en `.env`). Con `APP_NAME=Centro-Jubilados` el instalador se genera como `Centro-Jubilados-X.X.X-setup.exe`. Ese nombre también se usa para el acceso directo, el nombre en "Desinstalar programas" y la carpeta de datos en `%APPDATA%`.

##### Subir la Versión (obligatorio si ya hay instalaciones previas):
NativePHP solo re-ejecuta las migraciones cuando cambia la versión de la app (compara la versión instalada contra la nueva). Si no la incrementás, un cliente que ya tenga una versión instalada **no** correrá las nuevas migraciones (ni la de siembra). Incrementá la versión en `config/nativephp.php`:

```php
'version' => env('NATIVEPHP_APP_VERSION', '1.0.1'), // subir en cada release
```

##### Compilar Assets de Producción:
Generá los archivos estáticos de Vite (Bootstrap, FullCalendar, etc.):

`npm run build`

##### Limpiar Dependencias de Desarrollo:
Para evitar errores de clases no encontradas (como PHPUnit) en el empaquetado final:

```bash
rm -rf vendor/
composer install --no-dev --optimize-autoloader
```

##### Generar el Build:
Ejecutá el comando de NativePHP para Windows. Podés pasar la arquitectura como argumento (`x64` o `all`) para evitar el prompt interactivo:

`php artisan native:build win x64`

##### Resultado:
El instalador se generará en la carpeta `dist/` con el nombre `Centro-Jubilados-X.X.X-setup.exe` (según `APP_NAME` y la versión).

Nota: Una vez finalizado el build, recordá ejecutar `composer install` nuevamente (sin el flag --no-dev) para recuperar tus herramientas de desarrollo y testing locales.

##### Credenciales de prueba en la app instalada:
La migración de siembra deja estos usuarios disponibles en cada instalación:

-   **Admin**: `admin@admin.com` / `scjadmin`
-   **Prueba**: `test@test.com` / `test1234`

Desde la versión `1.1.2`, la migración `2026_10_07_000000_restore_initial_data.php`
recupera los usuarios y el catálogo inicial que dejaron de crearse al desactivar
la siembra anterior. Funciona tanto en instalaciones nuevas como al actualizar,
sin sobrescribir contraseñas ni datos existentes. Si configurás
`NATIVEPHP_APP_VERSION` en `.env`, actualizalo también a `1.1.2` (o una versión posterior)
antes de compilar, porque esa variable tiene prioridad sobre `config/nativephp.php`.

---

## Creación de usuarios

La cuenta `admin@admin.com` puede crear cuentas desde el menú de su nombre → **Crear usuario**.
El formulario de registro y su envío requieren sesión de administrador. Las cuentas nuevas
son usuarios comunes y el administrador conserva su sesión después de crearlas.

El permiso se guarda en `users.is_admin`, no se puede modificar desde el registro ni el perfil.
Para actualizar una instalación, ejecutar `php artisan migrate`; en NativePHP las migraciones
se aplican al iniciar la versión actualizada. La migración habilita la cuenta administradora
existente sin cambiar su contraseña ni los datos de las otras cuentas.

### Datos de demostración (carga manual)

Ejecutar `php artisan db:seed --class=DemoDataSeeder` para agregar socios, cuotas,
alquileres, utilería y movimientos ficticios identificados como DEMO. Para la base
local de NativePHP: `DB_DATABASE=database/nativephp.sqlite php artisan db:seed --class=DemoDataSeeder`.
La carga conserva registros existentes y contraseñas. No se ejecuta automáticamente
con las migraciones ni con DatabaseSeeder, para evitar datos ficticios en clientes.
Los datos se comparten entre las cuentas; no pertenecen exclusivamente al usuario test.
Los ejemplos incluyen cuotas del mes de carga y los dos anteriores, y eventos próximos.
Repetir la carga no duplica los ejemplos existentes; en meses nuevos agrega las cuotas de esos períodos.

### Instaladores 1.1.3 y pruebas

La edición normal se compila con `php artisan native:build win x64 --no-interaction`.
Para una edición aislada con datos de demostración: `python3 scripts/build-demo.py`.
Primero ejecutar `npm run build`. No compilar ambas ediciones simultáneamente.
La guía de pruebas y limitaciones está en [docs/PRUEBAS-1.1.3.md](docs/PRUEBAS-1.1.3.md).

### Habilitar y deshabilitar datos de prueba (1.1.4)

En la edición normal, el administrador puede abrir el menú de su nombre → **Datos de prueba**.
**Habilitar** carga los ejemplos para todos los usuarios. **Deshabilitar y quitar** pide
confirmación, respalda la base SQLite y elimina únicamente los registros creados por esa carga.
El registro interno `demo_records` permite reconocer los ejemplos aunque se editen sus nombres.
Las cuentas, los registros anteriores y los creados manualmente se conservan.
Si esos registros dependen de ejemplos DEMO, se conservan también los ejemplos necesarios y sus pagos;
la pantalla avisa cuántos quedaron. Resolver esos vínculos permite volver a intentar la limpieza.

Los respaldos se guardan en `storage/app/private/backups` (dentro de la carpeta de datos
propia de la aplicación en NativePHP), y se excluyen de los instaladores.
Si el respaldo falla, no se elimina ningún registro. La función de limpieza requiere SQLite.
Los ejemplos de instalaciones antiguas que no cuenten con marcas de procedencia no se eliminan
por coincidencia de nombre: se requiere identificar su origen antes de registrarlos para limpieza.
La versión normal no carga datos de prueba automáticamente. Es suficiente usar una sola instalación.

### Exportar respaldo de la base (1.1.6)

En la edición de escritorio, el administrador puede ir a su menú → **Respaldos** →
**Exportar respaldo**. La opción **Datos de prueba** queda separada para gestionar los ejemplos. El cuadro de guardado permite elegir el destino del archivo `.sqlite`.
Se crea una copia completa y consistente aunque la aplicación siga abierta. La exportación
no modifica la base original. En esta versión, la contraseña inicial de administración es
`scjadmin`; una contraseña personalizada no se reemplaza al actualizar.


### Importar un respaldo (1.1.7)

Desde el menú del administrador → **Respaldos** → **Importar respaldo** se puede elegir un archivo `.sqlite` exportado previamente. La acción avisa que reemplazará los datos, genera primero una copia automática, valida la base y aplica las migraciones pendientes. Si falla la importación, restaura la base previa.

### Correcciones de acceso y perfil (1.1.8)

Al cerrar sesión, la aplicación vuelve a la pantalla inicial. La pregunta de seguridad puede omitirse al actualizar otros datos del perfil; si se envía, sigue siendo obligatoria.

### Corrección de importación SQLite (1.1.9)

La importación pausa los workers de NativePHP, consolida y cierra SQLite antes de reemplazar el archivo, y limpia los sidecars WAL para evitar bloqueos o datos residuales. Ante un error, el administrador también verá el detalle técnico para facilitar el diagnóstico.
