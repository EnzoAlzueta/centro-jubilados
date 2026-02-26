<x-app-layout>

    <div class="container-fluid px-4 px-md-5 mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="p-2 fw-bold">Gestión de Utilería</h2>
            </div>

            <div class="d-flex gap-2">
                @if($showDisabled ?? false)
                <a href="{{ route('utilerias.index') }}" class="btn btn-outline-primary px-3">
                    <i class="bi bi-box-seam"></i> Ver Solo Activos
                </a>
                @else
                <a href="{{ route('utilerias.index', ['ver_deshabilitadas' => 1]) }}"
                    class="btn btn-outline-secondary px-3">
                    <i class="bi bi-box-seam"></i> Ver Todos (Incluir Deshabilitados)
                </a>
                @endif
                <a href="{{ route('utilerias.create') }}" class="btn btn-primary px-3">
                    <i class="bi bi-plus-circle"></i> Ingresar Nueva Utilería
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

            <table id="tabla-utilerias" class="table table-hover">
                <thead class="table-light ">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Stock Total</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($utilerias as $utileria)
                    <tr class="{{ $utileria->habilitado == 0 ? 'opacity-50 grayscale' : '' }}">
                        <td>{{ $utileria->id }}</td>
                        <td>{{ $utileria->nombre }}</td>
                        <td>{{ $utileria->stock_total }}</td>
                        <td>
                            @if($utileria->habilitado == 1)
                            <span class="bg-success-subtle text-success border border-success-subtle px-2 rounded">
                                Activo </span>
                            @else
                            <span class="bg-danger-subtle text-danger border border-danger-subtle px-2 rounded">
                                Deshabilitado </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('utilerias.edit', $utileria->id) }}" class="btn btn-outline-success">
                                    <i class="bi bi-pen"></i>
                                </a>

                                @if($utileria->habilitado == 1)
                                <form action="{{ route('utilerias.destroy', $utileria->id) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar esta utilería?');"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
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
            $('#tabla-utilerias').DataTable({
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