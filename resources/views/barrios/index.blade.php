<x-app-layout>

    <div class="container-fluid px-4 px-md-5 mt-3">    
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="p-2 fw-bold">Gestión de Barrios</h2>
            </div>
            
            <div>
                <a href="{{ route('barrios.crear.web') }}" class="btn btn-primary px-3">
                    <i class="bi bi-houses"></i> Ingresar Nuevo Barrio
                </a>
            </div>
        </div>                

    
        {{-- Tabla con estructura para DataTables --}}
        <div class="border rounded bg-white p-3">

            <table id="tabla-barrios" class="table table-hover">
                <thead class="table-light ">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Vacío: DataTables lo llena vía AJAX --}}
                </tbody>
            </table>
            
        </div>
    </div>
    

    {{-- Script para activar DataTables --}}
    <script type="module">
        $(document).ready(function () {
            $('#tabla-barrios').DataTable({
                
                ajax: {
                    "url": "/api/barrios",
                    "dataSrc" : ""
                },
                "columns": [
                    { "data": "id" },
                    { "data": "nombre" },
                    { 
                        "data": null,
                        "render": function (data, type, row) {
                            return `<button class="btn btn-outline-success"> <i class="bi bi-pen"></i></button> 
                            <button class="btn btn-outline-danger"> <i class="bi bi-trash"></i></button>`;
                        }
                    }
                ],
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