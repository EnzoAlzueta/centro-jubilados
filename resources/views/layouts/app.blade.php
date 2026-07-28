<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    {{-- Modales de confirmación y aviso reutilizables.
         Reemplazan a confirm()/alert() nativos: en la app de escritorio
         (Electron) el diálogo nativo deja la ventana sin foco de teclado y
         no se pueden usar los inputs hasta cambiar de ventana y volver. --}}
    <div class="modal fade" id="modal-confirmar" tabindex="-1" aria-labelledby="modal-confirmar-titulo"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-confirmar-titulo">Confirmar acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="modal-confirmar-mensaje" style="white-space: pre-line;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="modal-confirmar-aceptar">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-aviso" tabindex="-1" aria-labelledby="modal-aviso-titulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-aviso-titulo">Aviso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="modal-aviso-mensaje" style="white-space: pre-line;"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            function instancia(id) {
                const el = document.getElementById(id);
                return { el: el, modal: window.bootstrap.Modal.getOrCreateInstance(el) };
            }

            // Devuelve una promesa que resuelve true si el usuario acepta.
            window.confirmarAccion = function (mensaje, opciones) {
                opciones = opciones || {};
                return new Promise(function (resolve) {
                    const { el, modal } = instancia('modal-confirmar');
                    document.getElementById('modal-confirmar-mensaje').textContent = mensaje;
                    document.getElementById('modal-confirmar-titulo').textContent =
                        opciones.titulo || 'Confirmar acción';

                    const aceptar = document.getElementById('modal-confirmar-aceptar');
                    aceptar.textContent = opciones.textoAceptar || 'Aceptar';
                    aceptar.className = 'btn ' + (opciones.claseAceptar || 'btn-danger');

                    let aceptado = false;

                    function onAceptar() {
                        aceptado = true;
                        modal.hide();
                    }

                    function onCerrado() {
                        aceptar.removeEventListener('click', onAceptar);
                        el.removeEventListener('hidden.bs.modal', onCerrado);
                        resolve(aceptado);
                    }

                    aceptar.addEventListener('click', onAceptar);
                    el.addEventListener('hidden.bs.modal', onCerrado);
                    modal.show();
                });
            };

            // Reemplazo de alert(): resuelve cuando el usuario cierra el aviso.
            window.avisar = function (mensaje, opciones) {
                opciones = opciones || {};
                return new Promise(function (resolve) {
                    const { el, modal } = instancia('modal-aviso');
                    document.getElementById('modal-aviso-mensaje').textContent = mensaje;
                    document.getElementById('modal-aviso-titulo').textContent = opciones.titulo || 'Aviso';

                    function onCerrado() {
                        el.removeEventListener('hidden.bs.modal', onCerrado);
                        resolve();
                    }

                    el.addEventListener('hidden.bs.modal', onCerrado);
                    modal.show();
                });
            };

            // Para usar en onsubmit: frena el envío, pide confirmación y
            // recién entonces manda el formulario.
            window.confirmarEnvio = function (event, mensaje, opciones) {
                event.preventDefault();
                const form = event.target;
                window.confirmarAccion(mensaje, opciones).then(function (ok) {
                    if (ok) {
                        form.submit();
                    }
                });
                return false;
            };
        })();
    </script>

    @stack('scripts')
</body>

</html>