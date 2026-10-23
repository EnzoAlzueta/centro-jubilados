<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <h1 class="h2 fw-bold">Respaldos</h1>
                <p class="text-muted">Guarda una copia completa de la base de datos para recuperarla si surge algún problema.</p>

                @foreach(['success' => 'success', 'warning' => 'warning', 'error' => 'danger', 'info' => 'info'] as $key => $color)
                    @if(session($key))
                        <div class="alert alert-{{ $color }}" role="{{ $key === 'error' ? 'alert' : 'status' }}">{{ session($key) }}</div>
                    @endif
                @endforeach

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary bg-opacity-10 border-0 p-4">
                        <h2 class="h5 fw-bold text-primary mb-0"><i class="bi bi-database-down me-2" aria-hidden="true"></i>Exportar base de datos</h2>
                    </div>
                    <div class="card-body p-4">
                        <p>Elige dónde guardar el archivo de respaldo. La copia incluye usuarios y datos de socios, cuotas, alquileres y caja.</p>
                        <p class="text-muted small">La aplicación puede seguir abierta durante la exportación. El archivo se crea con una copia consistente de la base actual y no modifica los datos originales.</p>
                        <form method="POST" action="{{ route('backups.export') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-download me-2" aria-hidden="true"></i>Exportar respaldo
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-warning bg-opacity-10 border-0 p-4">
                        <h2 class="h5 fw-bold text-dark mb-0"><i class="bi bi-database-up me-2" aria-hidden="true"></i>Importar respaldo</h2>
                    </div>
                    <div class="card-body p-4">
                        <p>Restaura la base desde un archivo `.sqlite`. Se guardará una copia de seguridad de la base actual antes de reemplazarla.</p>
                        <p class="text-muted small">La importación reemplaza los datos actuales por los del respaldo. Se validará el archivo y se aplicarán las migraciones necesarias. Si algo falla, se restaura automáticamente la base anterior.</p>
                        <form method="POST" action="{{ route('backups.import') }}" onsubmit="confirmarEnvio(event, 'La importación reemplazará los datos actuales. Antes de hacerlo se guardará un respaldo automático. ¿Desea continuar?', { titulo: 'Importar respaldo', textoAceptar: 'Sí, importar respaldo', claseAceptar: 'btn-warning' });">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning text-dark">
                                <i class="bi bi-upload me-2" aria-hidden="true"></i>Seleccionar respaldo para importar
                            </button>
                        </form>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mt-3">Volver al inicio</a>
            </div>
        </div>
    </div>
</x-app-layout>
