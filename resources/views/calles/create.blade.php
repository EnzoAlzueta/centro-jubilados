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
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('calles.store') }}" method="POST" id="form-crear-calle">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Calle</label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                            placeholder="Ej: Av. San Martín" value="{{ old('nombre') }}" required>
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