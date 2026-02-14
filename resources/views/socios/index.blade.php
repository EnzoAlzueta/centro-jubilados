<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-3">    
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="p-2 fw-bold">Gestión de Socios</h2>
            </div>
            
            <div>
                <a href="" class="btn btn-primary px-3">
                    <i class="bi bi-person-plus"></i> Ingresar Nuevo Socio
                </a>
            </div>
        </div>                

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
                    {{-- Vacío: DataTables lo llena vía AJAX --}}
                </tbody>
            </table>
            
        </div>
    </div>
    

    {{-- Script para activar DataTables --}}
    <script type="module">
        $(document).ready(function () {
            $('#tabla-socios').DataTable({
                
                ajax: {
                    "url": "/api/socios",
                    "dataSrc" : ""
                },
                "columns": [
                    { "data": "numero_socio" },
                    { "data": null,
                       "render": function(data, type, row) {
                        return `${row.nombre} ${row.apellido}`.trim();
                       }
                     },
                    { "data": "dni" },
                    { "data": null,
                        "render": function(data, type, row) {
                            if(row.habilitado == 1) {
                                return `<span class="bg-success-subtle text-success border border-success-subtle px-2 rounded"> Activo </span>`;
                            } else {
                                return `<span class="bg-danger-subtle text-danger border border-danger-subtle px-2 rounded"> Deshabilitado </span>`; 
                            }
                        }
                     },
                    { 
                        "data": null,
                        "render": function (data, type, row) {
                            return `<a href="#" class="link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover me-3"> Generar Cartola </a> <button class="btn btn-outline-success"> <i class="bi bi-pen"></i></button> 
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