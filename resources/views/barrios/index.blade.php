<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Listado de Barrios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Botón con estilo Bootstrap --}}
                <div class="mb-4">
                    <a href="{{ route('barrios.create') }}" class="btn btn-primary">
                        + Nuevo Barrio
                    </a>
                </div>

                {{-- Tabla con estructura para DataTables --}}
                <table id="tabla-barrios" class="table table-striped table-hover" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Barrio</th>
                            <th>Acciones</th> {{-- Columna extra para futuros botones --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barrios as $barrio)
                            <tr>
                                <td>{{ $barrio->id }}</td>
                                <td>{{ $barrio->nombre }}</td>
                                <td>
                                    {{-- Botones de ejemplo pequeños --}}
                                    <button class="btn btn-sm btn-info text-white">Editar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- Script para activar DataTables --}}
    <script type="module">
        $(document).ready(function () {
            $('#tabla-barrios').DataTable({
                // En lugar de 'url', ponemos las frases aquí directamente:
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