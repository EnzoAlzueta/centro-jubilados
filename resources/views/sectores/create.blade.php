<x-app-layout>

    <div class="container-fluid px-4 px-md-5 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Ingresar Nueva Sector</h2>
            <a href="{{ route('sectores.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('sectores.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Sector</label>
                        <input type="text" name="nombre" id="nombre"
                            class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}"
                            required autofocus>
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span
                                class="text-muted small">(opcional)</span></label>
                        <textarea name="descripcion" id="descripcion" class="form-control" rows="2"
                            placeholder="Ej: Salón principal con capacidad para 200 personas">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="precio_base" class="form-label">Precio Base <span class="text-muted small">(se
                                cargará automáticamente en nuevas reservas)</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="precio_base" id="precio_base"
                                class="form-control @error('precio_base') is-invalid @enderror" placeholder="0.00"
                                min="0" value="{{ old('precio_base') }}">
                            @error('precio_base')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar
                        </button>
                        <a href="{{ route('sectores.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>