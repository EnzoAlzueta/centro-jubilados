<x-app-layout>

    <div class="container-fluid px-4 px-md-5 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Editar Sector</h2>
            <a href="{{ route('sectores.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('sectores.update', $sector->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Sector</label>
                        <input type="text" name="nombre" id="nombre"
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre', $sector->nombre) }}" required autofocus>
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="habilitado" name="habilitado"
                            value="1" {{ old('habilitado', $sector->habilitado) ? 'checked' : '' }}>
                        <label class="form-check-label" for="habilitado">Habilitado (Activo)</label>
                        <div class="form-text">Si desmarcas esta opción, el sector no aparecerá en la lista principal
                            ni podrá ser seleccionado en nuevos alquileres.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar
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