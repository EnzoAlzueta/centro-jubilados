<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Ingresar Nuevo Socio</h2>
            <a href="{{ route('socios.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <form action="{{ route('socios.update', $socio->id) }}" method="POST" id="form-editar-socio">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control"
                                value="{{ old('nombre', $socio->nombre) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" name="apellido" id="apellido" class="form-control"
                                value="{{ old('apellido', $socio->apellido) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="number" name="dni" id="dni" class="form-control"
                                value="{{ old('dni', $socio->dni) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control"
                                value="{{ old('fecha_nacimiento', $socio->fecha_nacimiento) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="barrio_id" class="form-label">Barrio</label>
                            <select name="barrio_id" id="barrio_id" class="form-select" required>
                                <option value="">Selecciona un barrio</option>
                                @foreach($barrios as $barrio)
                                <option value="{{ $barrio->id }}" {{ $socio->barrio_id == $barrio->id ? 'selected' : ''
                                    }}>{{ $barrio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="calle" class="form-label">Calle</label>
                            <input type="text" name="calle" id="calle" class="form-control"
                                value="{{ old('calle', $socio->calle) }}" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="altura" class="form-label">Altura</label>
                            <input type="text" name="altura" id="altura" class="form-control"
                                value="{{ old('altura', $socio->altura) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control"
                                value="{{ old('telefono', $socio->telefono) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email', $socio->email) }}">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Actualizar Socio</button>
                        <a href="{{ route('socios.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>