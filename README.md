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

```bash
composer setup
php artisan db:seed # Si hay nuevos seeders necesarios
```

O de forma manual:
1.  `composer install` - Si hubo cambios en `composer.json`.
2.  `npm install` - Si hubo cambios en `package.json`.
3.  `php artisan migrate` - Si hay nuevas migraciones.
4.  `npm run build` - Para actualizar los assets.

---
© 2026 Centro de Jubilados - Sistema de Gestión
