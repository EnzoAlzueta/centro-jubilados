<x-app-layout>
    
    <div class="container-fluid px-4 px-md-5 mt-3">
        <h2 class="mb-4 fw-bold">Resumen General</h2>
        
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2 text-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Total de Socios Activos</p>
                            <h3 class="fw-bold mb-0">150</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm p-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-2 text-success">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Ingresos del Mes (Caja)</p>
                            <h3 class="fw-bold mb-0">$2,500.00</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-md-5">
        <h5>Próximos Alquileres</h5>
        <div class="border rounded bg-white p-3">

            <table id="tabla-alquileres" class="table table-hover">
                <thead class="table-light ">
                    <tr>
                        <th scope="col">FECHA</th>
                        <th scope="col">EVENTO</th>
                        <th scope="col">CLIENTE</th>
                        <th scope="col">ESTADO</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Vacío: DataTables lo llena vía AJAX --}}
                </tbody>
            </table>
        </div>
    </div>
    <script type="module">
        $(document).ready(function () {
            $('#tabla-alquileres').DataTable({
                responsive: true,
                ajax: {
                    "url": "/api/alquileres",
                    "dataSrc" : ""
                },
                "columns": [
                    { "data": "Fecha" },
                    { "data": "Evento" },
                    { "data": "Cliente" },
                    { "data": "Estado" }
                    
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
