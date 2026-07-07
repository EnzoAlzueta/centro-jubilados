<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Gestión de Cuotas de Socios</h2>
            <a href="{{ route('cuotas.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-cash-coin"></i> Registrar Cuota
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-octagon-fill me-2"></i>
            <ul class="mb-0 d-inline-block">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Filtros --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('cuotas.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small text-muted text-uppercase fw-bold">Filtrar por Mes</label>
                        <select name="mes" class="form-select shadow-none" onchange="this.form.submit()">
                            <option value="">Todos los meses</option>
                            @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                            'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $index => $nombreMes)
                            <option value="{{ $index + 1 }}" {{ $mes==($index + 1) ? 'selected' : '' }}>{{
                                $nombreMes }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted text-uppercase fw-bold">Año</label>
                        <select name="anio" class="form-select shadow-none" onchange="this.form.submit()">
                            <option value="">Todos</option>
                            @for($i = now()->year; $i >= now()->year - 2; $i--)
                            <option value="{{ $i }}" {{ $anio==$i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted text-uppercase fw-bold">Estado</label>
                        <select name="estado" class="form-select shadow-none" onchange="this.form.submit()">
                            <option value="activas" {{ $estado=='activas' ? 'selected' : '' }}>Activas</option>
                            <option value="anuladas" {{ $estado=='anuladas' ? 'selected' : '' }}>Anuladas</option>
                            <option value="todas" {{ $estado=='todas' ? 'selected' : '' }}>Todas</option>
                        </select>
                    </div>
                    @if($mes || $anio || $estado != 'activas')
                    <div class="col-md-2">
                        <a href="{{ route('cuotas.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle me-2"></i>Limpiar
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <table id="tabla-cuotas" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha de Pago</th>
                            <th>Socio</th>
                            <th>Período (Mes/Año)</th>
                            <th>Monto</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cuotas as $cuota)
                        <tr class="{{ $cuota->trashed() ? 'text-muted bg-light' : '' }}">
                            <td data-order="{{ $cuota->fecha_pago ? \Carbon\Carbon::parse($cuota->fecha_pago)->format('Ymd') : 0 }}">
                                {{ $cuota->fecha_pago ? \Carbon\Carbon::parse($cuota->fecha_pago)->format('d/m/Y') :
                                'N/A' }}</td>
                            <td>
                                @if($cuota->socio)
                                <a href="{{ route('socios.show', $cuota->socio->id) }}"
                                    class="text-decoration-none text-dark fw-medium">
                                    {{ $cuota->socio->apellido }}, {{ $cuota->socio->nombre }}
                                </a>
                                @else
                                <span class="text-muted">Socio eliminado</span>
                                @endif
                            </td>
                            <td>{{ $cuota->mes }}/{{ $cuota->anio }}</td>
                            <td>${{ number_format($cuota->monto, 2) }}</td>
                            <td class="text-center">
                                @if($cuota->trashed())
                                <span
                                    class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2">Anulada</span>
                                @else
                                <span
                                    class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2">Activa</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('socios.cartola', $cuota->socio_id) }}?anio={{ $cuota->anio }}"
                                        class="btn btn-outline-primary btn-sm" title="Ver Cartola" target="_blank">
                                        <i class="bi bi-card-list"></i>
                                    </a>

                                    @if($cuota->trashed())
                                    <form action="{{ route('cuotas.restore', $cuota->id) }}" method="POST"
                                        id="restore-form-{{ $cuota->id }}">
                                        @csrf
                                        <button type="button" class="btn btn-outline-success btn-sm"
                                            title="Restaurar Cuota" onclick="confirmarRestauracion('{{ $cuota->id }}')">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('cuotas.destroy', $cuota->id) }}" method="POST"
                                        id="delete-form-{{ $cuota->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="reintegro" id="reintegro-{{ $cuota->id }}" value="0">
                                        <button type="button" class="btn btn-outline-danger btn-sm" title="Anular Pago"
                                            onclick="confirmarAnulacion('{{ $cuota->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmarAnulacion(id) {
            if (confirm('¿Estás seguro de anular este pago de cuota?')) {
                const reintegro = confirm('¿Deseas realizar la devolución del dinero y registrar el egreso en la caja?');
                document.getElementById('reintegro-' + id).value = reintegro ? '1' : '0';
                document.getElementById('delete-form-' + id).submit();
            }
        }

        function confirmarRestauracion(id) {
            if (confirm('¿Deseas restaurar esta cuota anulada? \n\nNota: Si habías realizado una devolución de dinero, deberás registrar manualmente el ingreso en caja si corresponde.')) {
                document.getElementById('restore-form-' + id).submit();
            }
        }
    </script>

    <script type="module">
        $(document).ready(function () {
            $('#tabla-cuotas').DataTable({
                "order": [[0, "desc"]], // Ordenar por fecha_pago por defecto
                language: {
                    "decimal": "",
                    "emptyTable": "No hay cuotas registradas",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar columna ascendente",
                        "sortDescending": ": activar para ordenar columna descendente"
                    }
                }
            });
        });
    </script>
</x-app-layout>