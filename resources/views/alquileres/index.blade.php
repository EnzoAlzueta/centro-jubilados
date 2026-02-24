<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0"><i class="bi bi-calendar-event me-2"></i>Gestión de Alquileres</h2>
            <div class="d-flex gap-2">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-0 py-2 shadow-sm" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-0 py-2 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> Error en la reserva. Revisa los campos.
                    <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
            </div>

            {{-- Contenedor para alertas dinámicas (AJAX) --}}
            <div id="ajax-alert-container"></div>
        </div>

        <div class="row g-4">
            {{-- Calendario --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            {{-- Formulario lateral --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h4 class="fw-bold mb-0">Nueva Reserva</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('alquileres.store') }}" method="POST" id="form-alquiler">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label small text-muted text-uppercase fw-bold">Sector / Salón</label>
                                <select name="sector_id" id="sector_id"
                                    class="form-select @error('sector_id') is-invalid @enderror" required>
                                    @foreach($sectores as $sector)
                                    <option value="{{ $sector->id }}" {{ old('sector_id')==$sector->id ? 'selected' : ''
                                        }}>{{ $sector->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('sector_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted text-uppercase fw-bold">Fecha del
                                    Evento</label>
                                <input type="date" name="fecha_evento"
                                    class="form-control @error('fecha_evento') is-invalid @enderror" id="fecha_evento"
                                    required min="{{ date('Y-m-d') }}" value="{{ old('fecha_evento') }}">
                                @error('fecha_evento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label small text-muted text-uppercase fw-bold">Tipo de
                                    Solicitante</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipo_cliente" id="es_socio"
                                            value="socio" {{ old('tipo_cliente', 'socio' )=='socio' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="es_socio">Socio</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipo_cliente" id="es_externo"
                                            value="externo" {{ old('tipo_cliente')=='externo' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="es_externo">Externo</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Campos para Socio --}}
                            <div id="campo-socio"
                                class="mb-3 {{ old('tipo_cliente', 'socio') == 'socio' ? '' : 'd-none' }}">
                                <label class="form-label small text-muted text-uppercase fw-bold">Seleccionar
                                    Socio</label>
                                <select name="socio_id" id="socio_id"
                                    class="form-select select2 @error('socio_id') is-invalid @enderror">
                                    <option value="">Seleccione un socio...</option>
                                    @foreach($socios as $socio)
                                    <option value="{{ $socio->id }}" {{ old('socio_id')==$socio->id ? 'selected' : ''
                                        }}>{{ $socio->apellido }}, {{ $socio->nombre }} (Nro: {{ $socio->numero_socio
                                        }})</option>
                                    @endforeach
                                </select>
                                @error('socio_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Campos para Externo --}}
                            <div id="campos-externo"
                                class="mb-3 {{ old('tipo_cliente') == 'externo' ? '' : 'd-none' }}">
                                <div class="mb-3">
                                    <label class="form-label small text-muted text-uppercase fw-bold">Nombre
                                        Completo</label>
                                    <input type="text" name="solicitante_externo"
                                        class="form-control select2 @error('solicitante_externo') is-invalid @enderror"
                                        placeholder="Ej: Juan Pérez" value="{{ old('solicitante_externo') }}">
                                    @error('solicitante_externo') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted text-uppercase fw-bold">DNI / CUIT</label>
                                    <input type="text" name="dni_solicitante_externo"
                                        class="form-control @error('dni_solicitante_externo') is-invalid @enderror"
                                        placeholder="00.000.000" value="{{ old('dni_solicitante_externo') }}">
                                    @error('dni_solicitante_externo') <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small text-muted text-uppercase fw-bold">Tipo de Evento</label>
                                <input type="text" name="tipo_evento"
                                    class="form-control @error('tipo_evento') is-invalid @enderror"
                                    placeholder="Ej: Cumpleaños, Reunión, etc." required
                                    value="{{ old('tipo_evento') }}">
                                @error('tipo_evento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label class="form-label small text-muted text-uppercase fw-bold">Precio
                                        Total</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="precio"
                                            class="form-control @error('precio') is-invalid @enderror" required
                                            value="{{ old('precio') }}">
                                        @error('precio') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small text-muted text-uppercase fw-bold">Seña
                                        Pagada</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="seña_pagada"
                                            class="form-control @error('seña_pagada') is-invalid @enderror"
                                            value="{{ old('seña_pagada', 0) }}">
                                        @error('seña_pagada') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small text-muted text-uppercase fw-bold">Utilería
                                    Requerida</label>
                                <div class="d-flex flex-column gap-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100"
                                        data-bs-toggle="modal" data-bs-target="#utileriaModal">
                                        <i class="bi bi-plus-circle me-1"></i> Seleccionar Utilería
                                    </button>
                                    <div id="utileria-summary" class="small text-muted text-center italic">
                                        0 artículos seleccionados
                                    </div>
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm">
                                <i class="bi bi-calendar-check me-2"></i> Confirmar Reserva
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Detalle de Reserva --}}
    <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Detalle del Alquiler</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3 text-primary" id="icon-container">
                            <i class="bi bi-info-circle fs-4"></i>
                        </div>
                        <div>
                            <h5 id="event-title" class="fw-bold mb-0"></h5>
                            <small id="event-date" class="text-muted"></small>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush small mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Solicitante:</span>
                            <span id="event-solicitante" class="fw-bold"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Sector:</span>
                            <span id="event-sector" class="fw-bold"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Estado:</span>
                            <span id="event-estado" class="badge"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Precio:</span>
                            <span id="event-precio" class="fw-bold"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Seña Pagada:</span>
                            <span id="event-seña" class="fw-bold"></span>
                        </li>
                    </ul>

                    <div id="event-utilerias-section" class="d-none mb-4">
                        <h6 class="fw-bold mb-2 small text-uppercase text-muted">Utilería Asignada</h6>
                        <ul id="event-utilerias-list" class="list-group list-group-flush border rounded-3 small">
                            <!-- Se carga vía JS -->
                        </ul>
                    </div>

                    <div id="payment-section" class="d-none border-top pt-4">
                        <h6 class="fw-bold mb-3 small text-uppercase text-muted">Registrar Pago de Saldo</h6>
                        <form id="form-registrar-pago" class="row g-2">
                            @csrf
                            <div class="col-8">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="monto" id="pago_monto" class="form-control" step="0.01"
                                        placeholder="Monto a pagar" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <i class="bi bi-cash-coin me-1"></i> Pagar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 d-flex justify-content-between">
                    <div>
                        <button type="button" id="btn-eliminar-alquiler"
                            class="btn btn-outline-danger btn-sm shadow-none">
                            <i class="bi bi-trash me-1"></i> Cancelar
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-light btn-sm shadow-none me-2"
                            data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" id="btn-editar-alquiler" class="btn btn-primary btn-sm shadow-none">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // TomSelect de socios
            new TomSelect("#socio_id", {
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
            // Inicializar Calendario
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                height: 700,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes'
                },
                events: "{{ route('alquileres.eventos') }}",
                dateClick: function (info) {
                    // Al hacer clic en un día, se completa la fecha en el formulario
                    document.getElementById('fecha_evento').value = info.dateStr;
                    // Resaltar visualmente o dar feedback
                    const target = document.getElementById('fecha_evento');
                    target.classList.add('bg-warning', 'bg-opacity-10');
                    setTimeout(() => target.classList.remove('bg-warning', 'bg-opacity-10'), 1000);
                },
                eventClick: function (info) {
                    const alquilerId = info.event.id;
                    document.getElementById('btn-editar-alquiler').setAttribute('data-id', alquilerId);
                    document.getElementById('btn-eliminar-alquiler').setAttribute('data-id', alquilerId);

                    fetch('/alquileres/' + alquilerId, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(response => {
                            if (!response.ok) throw new Error('Error al cargar datos');
                            return response.json();
                        })
                        .then(alquiler => {
                            document.getElementById('event-title').innerText = info.event.title;

                            const dateObj = new Date(alquiler.fecha_evento);
                            dateObj.setMinutes(dateObj.getMinutes() + dateObj.getTimezoneOffset());

                            document.getElementById('event-date').innerText = dateObj.toLocaleDateString('es-ES', {
                                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                            });

                            const solicitante = alquiler.socio
                                ? (alquiler.socio.apellido + ', ' + alquiler.socio.nombre)
                                : (alquiler.solicitante_externo || 'N/A');

                            document.getElementById('event-solicitante').innerText = solicitante;
                            document.getElementById('event-sector').innerText = alquiler.sector ? alquiler.sector.nombre : 'N/A';
                            document.getElementById('event-precio').innerText = '$' + Number(alquiler.precio || 0).toLocaleString('es-AR');
                            document.getElementById('event-seña').innerText = '$' + Number(alquiler.seña_pagada || 0).toLocaleString('es-AR');

                            const estadoBadge = document.getElementById('event-estado');
                            const estado = alquiler.estado || 'desconocido';
                            const estadoLabel = estado.charAt(0).toUpperCase() + estado.slice(1);
                            estadoBadge.innerText = estadoLabel;
                            estadoBadge.className = 'badge ';
                            if (estado === 'reservado') estadoBadge.classList.add('bg-info', 'text-dark');
                            else if (estado === 'pagado') estadoBadge.classList.add('bg-success');
                            else estadoBadge.classList.add('bg-danger');

                            const utilSection = document.getElementById('event-utilerias-section');
                            const utilList = document.getElementById('event-utilerias-list');
                            utilList.innerHTML = '';

                            if (alquiler.utilerias && alquiler.utilerias.length > 0) {
                                utilSection.classList.remove('d-none');
                                alquiler.utilerias.forEach(u => {
                                    const li = document.createElement('li');
                                    li.className = 'list-group-item d-flex justify-content-between align-items-center py-2';
                                    li.innerHTML = '<span>' + u.nombre + '</span><span class="badge bg-secondary rounded-pill">' + (u.pivot ? u.pivot.cantidad : 0) + '</span>';
                                    utilList.appendChild(li);
                                });
                            } else {
                                utilSection.classList.add('d-none');
                            }

                            const pendiente = alquiler.precio - alquiler.seña_pagada;
                            const paymentSection = document.getElementById('payment-section');
                            if (pendiente > 0) {
                                paymentSection.classList.remove('d-none');
                                document.getElementById('pago_monto').value = pendiente;
                                document.getElementById('pago_monto').max = pendiente;
                            } else {
                                paymentSection.classList.add('d-none');
                            }

                            const modalEl = document.getElementById('eventModal');
                            const modal = new window.bootstrap.Modal(modalEl);
                            modal.show();
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);
                            alert('No se pudieron cargar los detalles de la reserva.');
                        });
                }
            });

            // Registrar Pago de Saldo
            document.getElementById('form-registrar-pago').addEventListener('submit', function (e) {
                e.preventDefault();
                const alquilerId = document.getElementById('btn-editar-alquiler').getAttribute('data-id');
                const formData = new FormData(this);

                fetch(`/alquileres/${alquilerId}/pagar-saldo`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            // Cerrar modal actual
                            const modalInstance = window.bootstrap.Modal.getInstance(document.getElementById('eventModal'));
                            if (modalInstance) modalInstance.hide();

                            // Mostrar mensaje de éxito en el contenedor específico
                            const alertContainer = document.getElementById('ajax-alert-container');
                            const alertHtml = `
                            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                                <i class="bi bi-check-circle me-2"></i> ${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                            alertContainer.innerHTML = alertHtml;

                            // Recargar calendario
                            calendar.refetchEvents();
                        }
                    })
                    .catch(error => {
                        console.error('Payment error:', error);
                        alert('Error al procesar el pago.');
                    });
            });
            calendar.render();

            // Botón Editar Alquiler
            document.getElementById('btn-editar-alquiler').addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                fetch('/alquileres/' + id, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(response => response.json())
                    .then(alquiler => {
                        document.getElementById('sector_id').value = alquiler.sector_id;
                        document.getElementById('fecha_evento').value = alquiler.fecha_evento.split(' ')[0];
                        document.getElementsByName('tipo_evento')[0].value = alquiler.tipo_evento;
                        document.getElementsByName('precio')[0].value = alquiler.precio;
                        document.getElementsByName('seña_pagada')[0].value = alquiler.seña_pagada;

                        if (alquiler.socio_id) {
                            document.getElementById('es_socio').checked = true;
                            document.getElementById('socio_id').value = alquiler.socio_id;
                            document.getElementById('campo-socio').classList.remove('d-none');
                            document.getElementById('campos-externo').classList.add('d-none');
                        } else {
                            document.getElementById('es_externo').checked = true;
                            document.getElementsByName('solicitante_externo')[0].value = alquiler.solicitante_externo;
                            document.getElementsByName('dni_solicitante_externo')[0].value = alquiler.dni_solicitante_externo;
                            document.getElementById('campos-externo').classList.remove('d-none');
                            document.getElementById('campo-socio').classList.add('d-none');
                        }

                        document.querySelectorAll('#utileria-container input[type="number"]').forEach(input => input.value = 0);
                        if (alquiler.utilerias) {
                            alquiler.utilerias.forEach(u => {
                                const input = document.querySelector('#utileria-container input[value="' + u.id + '"]');
                                if (input) {
                                    const quantityInput = input.parentElement.querySelector('input[type="number"]');
                                    if (quantityInput) quantityInput.value = u.pivot.cantidad;
                                }
                            });
                        }

                        const form = document.getElementById('form-alquiler');
                        form.action = '/alquileres/' + id;
                        if (!form.querySelector('input[name="_method"]')) {
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'PUT';
                            form.appendChild(methodInput);
                        }

                        document.querySelector('button[type="submit"]').innerHTML = '<i class="bi bi-save me-2"></i> Guardar Cambios';
                        const modalInstance = window.bootstrap.Modal.getInstance(document.getElementById('eventModal'));
                        if (modalInstance) modalInstance.hide();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    });
            });

            // Botón Cancelar/Eliminar Alquiler
            document.getElementById('btn-eliminar-alquiler').addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                if (confirm('¿Está seguro de que desea cancelar esta reserva?')) {
                    fetch('/alquileres/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            if (response.ok) location.reload();
                            else alert('Error al cancelar la reserva.');
                        });
                }
            });

            // Toggle entre Socio y Externo
            const radioSocio = document.getElementById('es_socio');
            const radioExterno = document.getElementById('es_externo');
            const divSocio = document.getElementById('campo-socio');
            const divExterno = document.getElementById('campos-externo');

            radioSocio.addEventListener('change', function () {
                divSocio.classList.remove('d-none');
                divExterno.classList.add('d-none');
            });

            radioExterno.addEventListener('change', function () {
                divSocio.classList.add('d-none');
                divExterno.classList.remove('d-none');
            });

            // Lógica para actualizar el resumen de utilería
            const utilInputs = document.querySelectorAll('.utileria-input');
            const summaryDiv = document.getElementById('utileria-summary');

            function updateUtileriaSummary() {
                let count = 0;
                utilInputs.forEach(input => {
                    if (parseInt(input.value) > 0) count++;
                });

                if (count === 0) {
                    summaryDiv.textContent = '0 artículos seleccionados';
                    summaryDiv.classList.add('text-muted');
                    summaryDiv.classList.remove('text-primary', 'fw-bold');
                } else {
                    summaryDiv.textContent = count + (count === 1 ? ' artículo seleccionado' : ' artículos seleccionados');
                    summaryDiv.classList.remove('text-muted');
                    summaryDiv.classList.add('text-primary', 'fw-bold');
                }
            }

            utilInputs.forEach(input => {
                input.addEventListener('input', updateUtileriaSummary);
            });

            // Feedback al confirmar selección
            document.querySelector('#utileriaModal .btn-primary').addEventListener('click', function () {
                const count = Array.from(utilInputs).filter(i => parseInt(i.value) > 0).length;
                if (count > 0) {
                    // Podríamos usar un toast, pero por ahora un pequeño feedback visual en el botón principal
                    const btn = document.querySelector('[data-bs-target="#utileriaModal"]');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> ¡Utilería Agregada!';
                    btn.classList.replace('btn-outline-primary', 'btn-success');

                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.classList.replace('btn-success', 'btn-outline-primary');
                    }, 2000);
                }
            });

            // Ejecutar al cargar por si hay valores previos
            updateUtileriaSummary();

            // Asegurarse de que el modal no rompa el scroll cuando hay muchos items
            const utilModal = document.getElementById('utileriaModal');
            utilModal.addEventListener('hidden.bs.modal', function () {
                // Forzar limpieza de clases de Bootstrap si es necesario
                document.body.classList.remove('modal-open');
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
            });
        });


    </script>

    <style>
        .fc-event {
            cursor: pointer;
            border: none !important;
            padding: 4px 8px !important;
            border-radius: 6px !important;
        }

        .fc-event-title {
            font-weight: 600 !important;
            font-size: 0.85rem !important;
        }

        .fc-toolbar-title {
            font-weight: bold !important;
            text-transform: capitalize;
            font-size: 1.25rem !important;
        }

        .fc .fc-button-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            box-shadow: none !important;
        }

        .fc .fc-button-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .fc .fc-daygrid-day:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 0.375rem;
        }
    </style>

    {{-- Modal para selección de Utilería --}}
    <div class="modal fade" id="utileriaModal" tabindex="-1" aria-labelledby="utileriaModalLabel" aria-hidden="true"
        data-bs-backdrop="true" data-bs-keyboard="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" id="utileriaModalLabel">Seleccionar Utilería</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" style="max-height: 60vh; overflow-y: auto;">
                    <div class="row row-cols-1 row-cols-md-2 g-3" id="utileria-container">
                        @foreach($utilerias as $util)
                        <div class="col">
                            <div class="p-3 border rounded-3 bg-light bg-opacity-50">
                                <label class="form-label small text-muted fw-bold mb-2 d-block">
                                    {{ $util->nombre }}
                                    <span class="badge bg-secondary float-end">Stock Total: {{ $util->stock_total
                                        }}</span>
                                </label>
                                <div class="input-group input-group-sm">
                                    <input type="number" name="utilerias[{{ $loop->index }}][cantidad]"
                                        form="form-alquiler" class="form-control shadow-none utileria-input"
                                        placeholder="0" min="0"
                                        value="{{ old('utilerias.'.$loop->index.'.cantidad', 0) }}"
                                        data-id="{{ $util->id }}">
                                    <span class="input-group-text">unid.</span>
                                </div>
                                <input type="hidden" name="utilerias[{{ $loop->index }}][id]" form="form-alquiler"
                                    value="{{ $util->id }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-top-0 pb-4 px-4">
                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Confirmar
                        Selección</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>