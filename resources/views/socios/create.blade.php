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
                <form action="{{ route('socios.store') }}" method="POST" id="form-crear-socio">
                    @csrf

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="numero_socio" class="form-label">Número Socio</label>
                            <input type="number" name="numero_socio" id="numero_socio" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" name="apellido" id="apellido" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="number" name="dni" id="dni" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control"
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="barrio_id" class="form-label">Barrio</label>
                            <select name="barrio_id" id="barrio_id" class="form-select" required>
                                <option value="">Selecciona un barrio</option>
                                @foreach($barrios as $barrio)
                                <option value="{{ $barrio->id }}">{{ $barrio->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="calle" class="form-label">Calle</label>
                            <input type="text" name="calle" id="calle" class="form-control" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="altura" class="form-label">Altura</label>
                            <input type="text" name="altura" id="altura" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">Guardar Socio</button>
                        <a href="{{ route('socios.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
     <script type="module">
        new TomSelect("#barrio_id", {
            persist: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            render: {
                option_create: function(data, escape) {
                    return `<div class="create"><i class="bi bi-plus-circle text-info"></i> Agregar barrio: <strong>${escape(data.input)}</strong></div>`;
                },
                no_results: function(data, escape) {
                    return `<div class="no-results">No se encontró el barrio "${escape(data.input)}"</div>`;
                },
            },
            // Si el barrio no existe, se agrega en la base y en la lista como nueva opción.
            create: function(input, callback) {
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
    </script>
</x-app-layout>