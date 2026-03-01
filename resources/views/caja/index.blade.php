<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0">Movimientos de Caja</h2>
            <button type="button" class="btn btn-primary px-4 shadow-sm fw-bold" data-bs-toggle="modal"
                data-bs-target="#modalMovimiento">
                <i class="bi bi-plus-lg me-2"></i>Nuevo Movimiento
            </button>
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

        {{-- Filtros y Resumen --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <form action="{{ route('caja.index') }}" method="GET" id="filtroForm">
                        <label class="form-label small text-muted text-uppercase fw-bold">Filtrar por Período</label>
                        <div class="row g-2">
                            <div class="col-8">
                                <select name="mes" class="form-select shadow-none" onchange="this.form.submit()">
                                    @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                                    'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $index => $nombreMes)
                                    <option value="{{ $index + 1 }}" {{ $mes==($index + 1) ? 'selected' : '' }}>{{
                                        $nombreMes }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <select name="anio" class="form-select shadow-none" onchange="this.form.submit()">
                                    @for($i = now()->year; $i >= now()->year - 2; $i--)
                                    <option value="{{ $i }}" {{ $anio==$i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 h-100 bg-success bg-opacity-10">
                    <p class="text-success mb-0 small text-uppercase fw-bold">Total Ingresos</p>
                    <h3 class="fw-bold mb-0 text-success">${{ number_format($totalIngresos, 2) }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 h-100 bg-danger bg-opacity-10">
                    <p class="text-danger mb-0 small text-uppercase fw-bold">Total Egresos</p>
                    <h3 class="fw-bold mb-0 text-danger">${{ number_format($totalEgresos, 2) }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div
                    class="card border-0 shadow-sm p-3 h-100 {{ $saldoMensual >= 0 ? 'bg-primary' : 'bg-warning' }} bg-opacity-10">
                    <p
                        class="{{ $saldoMensual >= 0 ? 'text-primary' : 'text-warning' }} mb-0 small text-uppercase fw-bold">
                        Saldo Mensual</p>
                    <h3 class="fw-bold mb-0 {{ $saldoMensual >= 0 ? 'text-primary' : 'text-warning' }}">${{
                        number_format($saldoMensual, 2) }}</h3>
                </div>
            </div>
        </div>

        {{-- Tabla de Movimientos --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Detalle de Movimientos</h5>
                <table id="tabla-movimientos" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Categoría</th>
                            <th>Tipo</th>
                            <th class="text-end">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movimientos as $movimiento)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}</td>
                            <td>
                                {{ $movimiento->concepto }}
                                @if($movimiento->categoria == 'cuota' && !$movimiento->referencia?->pagado &&
                                $movimiento->tipo == 'ingreso')
                                <span class="badge bg-secondary ms-2">Anulada</span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2">
                                    {{ ucfirst($movimiento->categoria) }}
                                </span>
                            </td>
                            <td>
                                @if($movimiento->tipo == 'ingreso')
                                <span class="text-success fw-bold"><i class="bi bi-arrow-up-circle-fill me-1"></i>
                                    Ingreso</span>
                                @else
                                <span class="text-danger fw-bold"><i class="bi bi-arrow-down-circle-fill me-1"></i>
                                    Egreso</span>
                                @endif
                            </td>
                            <td
                                class="text-end fw-bold {{ $movimiento->tipo == 'ingreso' ? 'text-success' : 'text-danger' }}">
                                {{ $movimiento->tipo == 'ingreso' ? '+' : '-' }} ${{ number_format($movimiento->monto,
                                2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Nuevo Movimiento --}}
    <div class="modal fade" id="modalMovimiento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('caja.store') }}" method="POST" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Registrar Movimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select" required>
                            <option value="ingreso">Ingreso</option>
                            <option value="egreso">Egreso</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Concepto</label>
                        <input type="text" name="concepto" class="form-control"
                            placeholder="Ej: Pago de luz, Donación, etc." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="monto" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoría</label>
                        <select name="categoria" class="form-select">
                            <option value="manual">Manual</option>
                            <option value="servicios">Servicios</option>
                            <option value="mantenimiento">Mantenimiento</option>
                            <option value="insumos">Insumos</option>
                            <option value="otros">Otros</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Movimiento</button>
                </div>
            </form>
        </div>
    </div>

    <script type="module">
        $(document).ready(function () {
            $('#tabla-movimientos').DataTable({
                responsive: true,
                order: [[0, 'desc']],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                }
            });
        });
    </script>
</x-app-layout>