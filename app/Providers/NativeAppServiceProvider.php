<?php

namespace App\Providers;

use Native\Laravel\Facades\Window;
use Native\Laravel\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Window::open()
            ->title('Centro de Jubilados') // El título de la ventana
            ->width(1280) // Ancho por defecto
            ->height(800) // Alto por defecto
            ->center() // Que aparezca en el centro de la pantalla
            ->showDevTools(false); // Oculta la consola de desarrollador para el cliente
    }
    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}