<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-4">
        <div class="mb-4">
            <h2 class="fw-bold">Reportes del Sistema</h2>
            <p class="text-muted">Seleccione y configure el reporte que desea generar en formato PDF. Se abrirá una
                vista previa en una nueva pestaña, desde donde podrá imprimir o guardar el documento.</p>
        </div>

        {{-- Contenedor para el mensaje "sin datos" (se muestra en esta misma ventana) --}}
        <div id="reporte-alert-container">
            @if(session('error'))
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
        </div>

        <div class="row g-4">
            {{-- Reporte de Socios --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-primary bg-opacity-10 border-0 p-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-people-fill text-primary fs-3 me-3"></i>
                            <h5 class="fw-bold mb-0 text-primary">Resumen de Socios</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small">Genera un listado completo de todos los socios registrados,
                            incluyendo su barrio, contacto y estado actual (habilitado/deshabilitado).</p>
                        <div class="mt-4 pt-2 border-top">
                            <a href="{{ route('reportes.socios') }}"
                                onclick="event.preventDefault(); verReporte(this.href);"
                                class="btn btn-primary w-100 shadow-none">
                                <i class="bi bi-eye me-2"></i> Ver e Imprimir Reporte
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Reporte de Alquileres --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-success bg-opacity-10 border-0 p-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-event-fill text-success fs-3 me-3"></i>
                            <h5 class="fw-bold mb-0 text-success">Resumen de Alquileres</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small">Listado de reservas de sectores filtrado por período. Incluye
                            detalles del solicitante, fecha, sector y estado de la reserva.</p>

                        <form action="{{ route('reportes.alquileres') }}" method="GET" class="mt-3"
                            onsubmit="event.preventDefault(); verReporte(this.action + '?' + new URLSearchParams(new FormData(this)).toString());">
                            <div class="row g-2">
                                <div class="col-7">
                                    <select name="mes" class="form-select shadow-none small">
                                        @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
                                        'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $index =>
                                        $nombreMes)
                                        <option value="{{ $index + 1 }}" {{ now()->month == ($index + 1) ? 'selected' :
                                            '' }}>{{ $nombreMes }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-5">
                                    <select name="anio" class="form-select shadow-none small">
                                        @for($i = now()->year; $i >= now()->year - 2; $i--)
                                        <option value="{{ $i }}" {{ now()->year == $i ? 'selected' : '' }}>{{ $i }}
                                        </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100 mt-3 shadow-none">
                                <i class="bi bi-eye me-2"></i> Ver e Imprimir Reporte
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Reporte de Caja --}}
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-info bg-opacity-10 border-0 p-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-cash-coin text-info fs-3 me-3"></i>
                            <h5 class="fw-bold mb-0 text-info">Resumen de Cuentas</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small">Informe detallado de ingresos y egresos de caja por período. Incluye
                            totales por categoría y balance final mensual.</p>

                        <form action="{{ route('reportes.caja') }}" method="GET" class="mt-3"
                            onsubmit="event.preventDefault(); verReporte(this.action + '?' + new URLSearchParams(new FormData(this)).toString());">
                            <div class="row g-2">
                                <div class="col-7">
                                    <select name="mes" class="form-select shadow-none small">
                                        @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
                                        'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $index =>
                                        $nombreMes)
                                        <option value="{{ $index + 1 }}" {{ now()->month == ($index + 1) ? 'selected' :
                                            '' }}>{{ $nombreMes }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-5">
                                    <select name="anio" class="form-select shadow-none small">
                                        @for($i = now()->year; $i >= now()->year - 2; $i--)
                                        <option value="{{ $i }}" {{ now()->year == $i ? 'selected' : '' }}>{{ $i }}
                                        </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info w-100 mt-3 shadow-none text-white">
                                <i class="bi bi-eye me-2"></i> Ver e Imprimir Reporte
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Muestra un mensaje de aviso en esta misma ventana (sin abrir pestañas).
        function mostrarMensajeReporte(msg) {
            const c = document.getElementById('reporte-alert-container');
            c.innerHTML =
                '<div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">' +
                '<i class="bi bi-exclamation-triangle-fill me-2"></i> ' + msg +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>';
            c.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // Antes de abrir el preview, consulta (?check=1) si el reporte tiene datos.
        // Con datos: abre la vista previa en una nueva pestaña.
        // Sin datos: muestra el mensaje en la ventana actual, sin abrir nada.
        async function verReporte(baseUrl) {
            const sep = baseUrl.includes('?') ? '&' : '?';
            try {
                const resp = await fetch(baseUrl + sep + 'check=1', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await resp.json();
                if (data.has_data) {
                    window.open(baseUrl, '_blank');
                } else {
                    mostrarMensajeReporte(data.message);
                }
            } catch (e) {
                // Ante un error inesperado, intentamos abrir el reporte directamente.
                window.open(baseUrl, '_blank');
            }
        }
    </script>
</x-app-layout>