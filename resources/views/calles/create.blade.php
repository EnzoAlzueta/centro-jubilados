<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Ingresar Nueva Calle</h2>
            <a href="{{ route('calles.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 py-2 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> Error en el formulario. Revisa los campos.
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <form action="{{ route('calles.store') }}" method="POST" id="form-crear-calle">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Calle</label>
                        <input type="text" name="nombre" id="nombre"
                            class="form-control @error('nombre') is-invalid @enderror" placeholder="Ej: Av. San Martín"
                            value="{{ old('nombre') }}" required>
                        @error('nombre')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">Guardar Calle</button>
                        <a href="{{ route('calles.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>