<x-app-layout>

    <div class="container-fluid px-4 px-md-5 mt-3">    
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="p-2 fw-bold">Gestión de Alquileres</h2>
            </div>
        </div>                
        <div class="row">
            <div class="col-7 bg-white rounded p-3 me-5">
                <div id="calendar"></div>

            </div>
            <div class="col bg-white ms-5 rounded p-3">
                <div class="container rounded shadow p-4">
                    <h3>Nuevo Alquiler</h3>
                    
                    <div class="row">
                        <label for="" class="form-label">Fecha</label>
                        <input type="datetime" class="form-control" name="" id=" ">
                    </div>
                    
                    <div class="row">
                        <label for="" class="form-label">Datos solicitante</label>
                        <select class="form-control" name="" id=""></select>
                    </div>
                    
                    <div class="row">
                        <label for="" class="form-label">Tipo de Evento</label>
                        <input class="form-control" type="text">
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="" class="form-label">Precio</label>
                            <input class="form-control" type="number" name="" id="">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Seña</label>
                            <input class="form-control" type="number" name="" id="">
                        </div>
                    </div>

                    <div class="row">
                        <h5>Utilería requerida</h5>
                    </div>
                    
                    <div class="row">
                        <label for="" class="form-label">Cantidad de Sillas</label>
                        <input class="form-control" type="number" name="" id="">
                    </div>
                    
                    <div class="row">
                        <label for="" class="form-label">Cantidad de Mesas</label>
                        <input class="form-control" type="number" name="" id="">
                    </div>
                    
                    <div class="row">
                        <div class="col-12 text-end m-2">
                            <button class="btn btn-primary"><i class="bi bi-floppy"></i> Guardar Alquiler</button>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    
    </div>
    

    <script>
        
      document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar')
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            height: 650
        })
        calendar.render()
      });

    </script>
</x-app-layout>