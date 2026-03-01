<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Editar Calle</h2>
            <a href="{{ route('calles.index') }}" class="btn btn-outline-secondary">
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
                <form action="{{ route('calles.update', $calle->id) }}" method="POST" id="form-editar-calle">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="hidden" name="habilitado" value="0">
                            <input class="form-check-input" type="checkbox" name="habilitado" id="habilitado" value="1"
                                @checked(old('habilitado', $calle->habilitado) == 1)>
                            <label class="form-check-label" for="habilitado">
                                Habilitada
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Calle</label>
                        <input type="text" name="nombre" id="nombre"
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre', $calle->nombre) }}" required>
                        @error('nombre')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Actualizar Calle</button>
                        <a href="{{ route('calles.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>