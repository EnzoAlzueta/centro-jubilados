<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Registrar Pago de Cuota</h2>
            <a href="{{ route('cuotas.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
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

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i>
                    <ul class="mb-0 d-inline-block">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('cuotas.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="socio_id" class="form-label">Socio</label>
                            <select name="socio_id" id="socio_id" class="form-select" required>
                                <option value="">Selecciona un socio</option>
                                @foreach($socios as $socio)
                                <option value="{{ $socio->id }}" {{ old('socio_id')==$socio->id ? 'selected' : '' }}>
                                    {{ $socio->apellido }}, {{ $socio->nombre }} (N° {{ $socio->numero_socio ??
                                    $socio->id }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="mes" class="form-label">Mes</label>
                            <select name="mes" id="mes" class="form-select" required>
                                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}" {{ old('mes', date('n'))==$i
                                    ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->locale('es')->monthName }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="anio" class="form-label">Año</label>
                            <input type="number" name="anio" id="anio" class="form-control"
                                value="{{ old('anio', date('Y')) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="monto" class="form-label">Monto Abonado ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" name="monto" id="monto" class="form-control"
                                    value="{{ old('monto', 3000) }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                            <input type="date" name="fecha_pago" id="fecha_pago" class="form-control"
                                value="{{ old('fecha_pago', date('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Registrar
                            Pago</button>
                        <a href="{{ route('cuotas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="module">
        new TomSelect("#socio_id", {
            persist: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    </script>
</x-app-layout>