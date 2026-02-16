<x-app-layout>

    <div class="container-fluid px-4 px-md-5 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Ingresar Nueva Utilería</h2>
            <a href="{{ route('utilerias.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('utilerias.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Utilería</label>
                        <input type="text" name="nombre" id="nombre"
                            class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}"
                            required autofocus>
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock_total" class="form-label">Stock Total</label>
                        <input type="number" name="stock_total" id="stock_total"
                            class="form-control @error('stock_total') is-invalid @enderror"
                            value="{{ old('stock_total', 0) }}" min="0" required>
                        @error('stock_total')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar
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