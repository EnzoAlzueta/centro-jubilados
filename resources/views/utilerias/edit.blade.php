<x-app-layout>

    <div class="container-fluid px-4 px-md-5 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Editar Utilería</h2>
            <a href="{{ route('utilerias.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 py-2 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> Error al actualizar. Revisa los campos.
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <form action="{{ route('utilerias.update', $utileria->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Utilería</label>
                        <input type="text" name="nombre" id="nombre"
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre', $utileria->nombre) }}" required autofocus>
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock_total" class="form-label">Stock Total</label>
                        <input type="number" name="stock_total" id="stock_total"
                            class="form-control @error('stock_total') is-invalid @enderror"
                            value="{{ old('stock_total', $utileria->stock_total) }}" min="0" required>
                        @error('stock_total')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="habilitado" name="habilitado"
                            value="1" {{ old('habilitado', $utileria->habilitado) ? 'checked' : '' }}>
                        <label class="form-check-label" for="habilitado">Habilitado (Activo)</label>
                        <div class="form-text">Si desmarcas esta opción, la utilería no aparecerá en la lista principal
                            ni podrá ser seleccionada en nuevos alquileres.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Actualizar
                        </button>
                        <a href="{{ route('utilerias.index') }}" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>