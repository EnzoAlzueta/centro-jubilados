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
-   **Contraseña**: `enzoadmin`

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

-   **Admin**: `admin@admin.com` / `enzoadmin`
-   **Prueba**: `test@test.com` / `test1234`

---