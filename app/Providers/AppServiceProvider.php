<?php

namespace App\Providers;

use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra cualquier servicio de la aplicación.
     */
    public function register(): void
    {
    //
    }

    /**
     * Inicializa cualquier servicio de la aplicación.
     */
    public function boot(): void
    {
        // Paginación: la vista Tailwind de Laravel usa clases (w-5/h-5) que no están en app.scss; en Electron los SVG crecen desmesuradamente.
        AbstractPaginator::useBootstrapFive();
    }
}
