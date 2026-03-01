<x-app-layout>

    <div class="container-fluid px-4 px-md-5 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Detalles del Socio</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('socios.edit', $socio->id) }}" class="btn btn-outline-success">
                    <i class="bi bi-pen"></i> Editar Socio
                </a>
                <a href="{{ route('socios.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver a Socios
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h4 class="card-title fw-bold mb-0">Socio N° {{ $socio->numero_socio }}</h4>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold mb-1">Nombre Completo</label>
                            <p class="fs-5 mb-0">{{ $socio->nombre }} {{ $socio->apellido }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold mb-1">DNI</label>
                            <p class="fs-5 mb-0">{{ $socio->dni }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold mb-1">Fecha de Nacimiento</label>
                            <p class="fs-5 mb-0">{{ \Carbon\Carbon::parse($socio->fecha_nacimiento)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold mb-1">Contacto</label>
                            <p class="fs-5 mb-0">
                                @if($socio->telefono)
                                <i class="bi bi-telephone text-muted me-2"></i>{{ $socio->telefono }}<br>
                                @endif
                                @if($socio->email)
                                <i class="bi bi-envelope text-muted me-2"></i>{{ $socio->email }}<br>
                                @endif
                                @if(!$socio->telefono && !$socio->email)
                                <span class="text-muted fst-italic">Sin datos de contacto</span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold mb-1">Dirección</label>
                            <p class="fs-5 mb-0">
                                Calle {{ $socio->calle->nombre ?? 'Sin especificar' }} {{ $socio->altura }}<br>
                                Barrio {{ $socio->barrio->nombre ?? 'Sin especificar' }}
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold mb-1">Estado</label>
                            <div>
                                @if($socio->habilitado == 1)
                                <span
                                    class="bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded fs-6">
                                    Activo
                                </span>
                                @else
                                <span
                                    class="bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded fs-6">
                                    Deshabilitado
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom-0 pt-4">
                            <h5 class="card-title fw-bold mb-0">
                                <i class="bi bi-card-list me-2 text-primary"></i>Cartola de Pagos
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <p class="text-muted small mb-4">Genera el comprobante anual de pagos del socio para el año
                                seleccionado.</p>
                            <form action="{{ route('socios.cartola', $socio->id) }}" method="GET" target="_blank"
                                class="row g-3">
                                <div class="col-7">
                                    <select name="anio" class="form-select shadow-none">
                                        @for($i = now()->year + 1; $i >= now()->year - 3; $i--)
                                        <option value="{{ $i }}" {{ $i==now()->year ? 'selected' : '' }}>Año {{ $i }}
                                        </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-5">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-file-pdf"></i> Generar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>