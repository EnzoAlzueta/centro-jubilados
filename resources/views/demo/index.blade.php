<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <h1 class="h2 fw-bold">Datos de prueba</h1>
                <p class="text-muted">Cargue ejemplos para practicar y quítelos cuando ya no los necesite.</p>

                @foreach(['success' => 'success', 'warning' => 'warning', 'error' => 'danger'] as $key => $color)
                    @if(session($key))
                        <div class="alert alert-{{ $color }}" role="{{ $key === 'error' ? 'alert' : 'status' }}">{{ session($key) }}</div>
                    @endif
                @endforeach
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">Confirme que desea quitar los datos de prueba antes de continuar.</div>
                @endif

                <div class="alert alert-info">
                    Los datos son compartidos por todos los usuarios y afectan los reportes, saldos de caja y reservas.
                    Las cuentas y contraseñas se conservan al quitar los ejemplos.
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary bg-opacity-10 border-0 p-4">
                        <h2 class="h5 fw-bold text-primary mb-0"><i class="bi bi-database me-2" aria-hidden="true"></i>Ejemplos del sistema</h2>
                    </div>
                    <div class="card-body p-4">
                        @if(array_sum($counts) > 0)
                            <p class="fw-bold">Hay datos de prueba cargados.</p>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <caption class="visually-hidden">Registros de prueba disponibles para quitar</caption>
                                    <thead><tr><th scope="col">Tipo de dato</th><th scope="col" class="text-end">Cantidad</th></tr></thead>
                                    <tbody>
                                        @foreach($labels as $table => $label)
                                            <tr><th scope="row" class="fw-normal">{{ $label }}</th><td class="text-end">{{ $counts[$table] }}</td></tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <p>Al deshabilitarlos, se guardará un respaldo y se eliminarán los registros creados por la carga DEMO, incluso si fueron editados.</p>
                            <p class="text-muted small">Los registros que cree manualmente se conservarán. Si utilizan elementos DEMO, esos elementos también se conservarán para no romper sus relaciones.</p>
                            <form method="POST" action="{{ route('demo.destroy') }}" onsubmit="confirmarEnvio(event, 'Se quitarán los datos de prueba registrados. Los registros nuevos creados manualmente y los elementos que utilicen se conservarán. Se guardará un respaldo antes de borrar. ¿Desea continuar?', { titulo: 'Deshabilitar datos de prueba', textoAceptar: 'Sí, quitar datos de prueba' });">
                                @csrf
                                @method('DELETE')
                                <div class="form-check mb-3">
                                    <input id="confirmar" name="confirmar" value="1" type="checkbox" class="form-check-input" required>
                                    <label for="confirmar" class="form-check-label">Confirmo que quiero quitar los datos de prueba cargados.</label>
                                </div>
                                <button type="submit" class="btn btn-outline-danger">Deshabilitar y quitar datos de prueba</button>
                            </form>
                        @else
                            <p class="fw-bold">No hay datos de prueba registrados.</p>
                            <p>Se agregarán seis socios, cuotas pagadas y pendientes, cinco alquileres en distintos estados, utilería y movimientos de ingresos y egresos.</p>
                            <p class="text-muted small">Los ejemplos se identifican como DEMO. La carga conserva los datos existentes y las contraseñas.</p>
                            <form method="POST" action="{{ route('demo.store') }}" onsubmit="confirmarEnvio(event, 'Los ejemplos estarán disponibles para todos los usuarios y se incluirán en caja y reportes. ¿Desea cargarlos?', { titulo: 'Habilitar datos de prueba', textoAceptar: 'Sí, cargar ejemplos', claseAceptar: 'btn-primary' });">
                                @csrf
                                <button type="submit" class="btn btn-primary">Habilitar datos de prueba</button>
                            </form>
                        @endif
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mt-3">Volver al inicio</a>
            </div>
        </div>
    </div>
</x-app-layout>
