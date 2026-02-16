<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="p-2 fw-bold">Gestión de Socios</h2>
            </div>

            <div>
                <a href="{{ route('socios.create') }}" class="btn btn-primary px-3">
                    <i class="bi bi-person-plus"></i> Ingresar Nuevo Socio
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

            <table id="tabla-socios" class="table table-hover align-middle">
                <thead class="table-light ">
                    <tr>
                        <th scope="col">N° Socio</th>
                        <th scope="col">Nombre y Apellido</th>
                        <th scope="col">DNI</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($socios as $socio)
                    <tr>
                        <td>{{ $socio->numero_socio }}</td>
                        <td>{{ $socio->nombre }} {{ $socio->apellido }}</td>
                        <td>{{ $socio->dni }}</td>
                        <td>
                            @if($socio->habilitado == 1)
                            <span class="bg-success-subtle text-success border border-success-subtle px-2 rounded">
                                Activo </span>
                            @else
                            <span class="bg-danger-subtle text-danger border border-danger-subtle px-2 rounded">
                                Deshabilitado </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="#"
                                    class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover me-3">
                                    Generar Cartola </a>
                                <a href="{{ route('socios.edit', $socio->id) }}" class="btn btn-outline-success">
                                    <i class="bi bi-pen"></i>
                                </a>

                                <form action="{{ route('socios.destroy', $socio->id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar este socio?');"
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
            $('#tabla-socios').DataTable({
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