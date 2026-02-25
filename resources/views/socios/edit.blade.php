<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Editar Socio: {{$socio->apellido}}, {{$socio->nombre}}</h2>
            <a href="{{ route('socios.index') }}" class="btn btn-outline-secondary">
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
                <form action="{{ route('socios.update', $socio->id) }}" method="POST" id="form-editar-socio">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <label for="numero_socio" class="form-label">Numero Socio</label>
                            <input type="text" name="numero_socio" class="form-control" id="numero_socio" readonly
                                value=" {{ old('numero_socio', $socio->numero_socio) }} ">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="hidden" name="habilitado" value="0">
                                <input class="form-check-input" type="checkbox" name="habilitado" id="habilitado"
                                    value="1" @checked(old('habilitado', $socio->habilitado) == 1)>
                                <label class="form-check-label" for="habilitado">
                                    Habilitado
                                </label>
                            </div>
                        </div>
                    </div>
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
                            <label for="calle_id" class="form-label">Calle</label>
                            <select name="calle_id" id="calle_id" class="form-select" required>
                                <option value="">Selecciona una calle</option>
                                @foreach($calles as $calle)
                                <option value="{{ $calle->id }}" {{ old('calle_id', $socio->calle_id) == $calle->id ?
                                    'selected' : '' }}>{{ $calle->nombre }}</option>
                                @endforeach
                            </select>
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

    <script type="module">
        // --- TomSelect para Barrio (Código de tu compañera) ---
        new TomSelect("#barrio_id", {
            persist: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            render: {
                option_create: function (data, escape) {
                    return `<div class="create"><i class="bi bi-plus-circle text-info"></i> Agregar barrio: <strong>${escape(data.input)}</strong></div>`;
                },
                no_results: function (data, escape) {
                    return `<div class="no-results">No se encontró el barrio "${escape(data.input)}"</div>`;
                },
            },
            create: function (input, callback) {
                const payload = {
                    nombre: input,
                    is_ajax: true
                };

                fetch("{{ route('barrios.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                })
                    .then(response => {
                        if (!response.ok) throw response;
                        return response.json();
                    })
                    .then(json => {
                        callback({ value: json.id, text: json.nombre });
                    })
                    .catch(async (error) => {
                        const data = await error.json();
                        if (error.status === 422) {
                            console.log(data.errors.nombre[0]);
                        } else {
                            console.log('Error de conexión con el servidor.' + data);
                        }
                        callback(false);
                    });
            }
        });

        // --- TomSelect para Calle (Tu código) ---
        new TomSelect("#calle_id", {
            persist: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            render: {
                option_create: function (data, escape) {
                    return `<div class="create"><i class="bi bi-plus-circle text-info"></i> Agregar calle: <strong>${escape(data.input)}</strong></div>`;
                },
                no_results: function (data, escape) {
                    return `<div class="no-results">No se encontró la calle "${escape(data.input)}"</div>`;
                },
            },
            create: function (input, callback) {
                const payload = {
                    nombre: input,
                    habilitado: 1,
                    is_ajax: true
                };

                fetch("{{ route('calles.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                })
                    .then(response => {
                        if (!response.ok) throw response;
                        return response.json();
                    })
                    .then(json => {
                        callback({ value: json.id, text: json.nombre });
                    })
                    .catch(async (error) => {
                        console.log('Error creando la calle.');
                        callback(false);
                    });
            }
        });
    </script>
</x-app-layout>