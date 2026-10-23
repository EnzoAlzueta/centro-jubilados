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
        // Centrado aproximado para una pantalla típica 1920x1080. Si la pantalla
        // es más chica, la ventana quedará pegada al borde superior izquierdo
        // pero seguirá siendo redimensionable. Se evita ->center() porque en
        // la versión de NativePHP instalada el trait HasPositioner no está
        // aplicado a PendingOpenWindow y el método no está disponible.
        Window::open()
            ->title('Centro de Jubilados') // El título de la ventana
            ->width(1280) // Ancho por defecto
            ->height(800) // Alto por defecto
            ->position(320, 140) // Centrado aproximado en 1920x1080
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