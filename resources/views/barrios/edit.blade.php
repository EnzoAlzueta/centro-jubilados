<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Editar Barrio</h2>
            <a href="{{ route('barrios.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('barrios.update', $barrio->id) }}" method="POST" id="form-editar-barrio">
                    @csrf
                    @method('PUT')

                    {{-- Campo Nombre (Input normal de Bootstrap) --}}
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Barrio</label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                            value="{{ old('nombre', $barrio->nombre) }}" required>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Actualizar Barrio</button>
                        <a href="{{ route('barrios.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>