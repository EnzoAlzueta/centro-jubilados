<x-app-layout>

    <div class="container-fluid px-4 px-md-5 mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="p-2 fw-bold">Gestión de Calles</h2>
            </div>

            <div>
                <a href="{{ route('calles.create') }}" class="btn btn-primary px-3">
                    <i class="bi bi-signpost-split"></i> Ingresar Nueva Calle
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
        @endif

        {{-- Tabla con estructura para DataTables --}}
        <div class="border rounded bg-white p-3">

            <table id="tabla-calles" class="table table-hover">
                <thead class="table-light ">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($calles as $calle)
                    <tr>
                        <td>{{ $calle->id }}</td>
                        <td>{{ $calle->nombre }}</td>
                        <td>
                            @if($calle->habilitado == 1)
                            <span class="bg-success-subtle text-success border border-success-subtle px-2 rounded">
                                Activa </span>
                            @else
                            <span class="bg-danger-subtle text-danger border border-danger-subtle px-2 rounded">
                                Deshabilitada </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('calles.edit', $calle->id) }}" class="btn btn-outline-success">
                                    <i class="bi bi-pen"></i>
                                </a>

                                <form action="{{ route('calles.destroy', $calle->id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de deshabilitar esta calle?');"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>


    {{-- Script para activar DataTables --}}
    <script type="module">
        $(document).ready(function () {
            $('#tabla-calles').DataTable({
                language: {
                    "decimal": "",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar columna ascendente",
                        "sortDescending": ": activar para ordenar columna descendente"
                    }
                }
            });
        });
    </script>
</x-app-layout>