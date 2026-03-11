<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Registrar Pago de Cuota</h2>
            <a href="{{ route('cuotas.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i>
                    <ul class="mb-0 d-inline-block">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('cuotas.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-9 mb-3">
                            <label for="socio_id" class="form-label">Socio</label>
                            <select name="socio_id" id="socio_id" class="form-select" required>
                                <option value="">Selecciona un socio</option>
                                @foreach($socios as $socio)
                                <option value="{{ $socio->id }}" {{ old('socio_id')==$socio->id ? 'selected' : '' }}>
                                    {{ $socio->apellido }}, {{ $socio->nombre }} (N° {{ $socio->numero_socio ??
                                    $socio->id }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="anio" class="form-label">Año a abonar</label>
                            <input type="number" name="anio" id="anio" class="form-control fw-bold text-center"
                                value="{{ old('anio', date('Y')) }}" required>
                            <small class="text-muted">Puede cambiar el año</small>
                        </div>
                    </div>

                    <div class="mb-4" id="meses-container" style="display: none;">
                        <label class="form-label d-flex justify-content-between align-items-center mb-3">
                            <span>Seleccione los meses a pagar</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-3-meses">3 Meses</button>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-6-meses">6 Meses</button>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-12-meses">12 Meses</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-1" id="btn-limpiar">Limpiar</button>
                            </div>
                        </label>
                        <div class="row g-2">
                            @for ($i = 1; $i <= 12; $i++)
                            <div class="col-4 col-md-3 col-lg-2">
                                <input type="checkbox" name="meses[]" value="{{ $i }}" id="mes_{{ $i }}" class="btn-check mes-checkbox" autocomplete="off" {{ in_array($i, old('meses', [])) ? 'checked' : '' }}>
                                <label class="btn btn-outline-secondary w-100 mes-label d-flex flex-column align-items-center py-2" for="mes_{{ $i }}">
                                    <span class="fs-5 mb-1 text-capitalize fw-bold">{{ \Carbon\Carbon::create()->month($i)->locale('es')->shortMonthName }}</span>
                                    <i class="bi bi-circle status-icon"></i>
                                </label>
                            </div>
                            @endfor
                        </div>
                        <div class="form-text mt-2">
                            <i class="bi bi-info-circle"></i> Los meses en <span class="text-success fw-bold">verde</span> ya están pagados en el año seleccionado.
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="monto" class="form-label fw-bold">Monto <span class="text-primary">POR MES</span> ($)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white border-primary"><i class="bi bi-currency-dollar"></i></span>
                                <input type="number" step="0.01" min="0" name="monto" id="monto" class="form-control border-primary"
                                    value="{{ old('monto', 3000) }}" required>
                            </div>
                            <div class="form-text">Este monto se registrará por cada mes seleccionado. <br>Ej: Si selecciona 3 meses a $3000, abonará un total de $9000.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                            <input type="date" name="fecha_pago" id="fecha_pago" class="form-control"
                                value="{{ old('fecha_pago', date('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Registrar
                            Pago</button>
                        <a href="{{ route('cuotas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="module">
        const socioSelect = new TomSelect("#socio_id", {
            persist: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        const anioInput = document.getElementById('anio');
        const mesesContainer = document.getElementById('meses-container');
        const mesesCheckboxes = document.querySelectorAll('.mes-checkbox');
        const labels = document.querySelectorAll('.mes-label');
        const form = document.querySelector('form');

        // Función para cargar los meses pagados
        const checkMesesPagados = async () => {
            const socioId = document.getElementById('socio_id').value;
            const anio = anioInput.value;

            if (socioId && anio) {
                mesesContainer.style.display = 'block';
                
                try {
                    const response = await fetch(`/cuotas/pagadas/${socioId}/${anio}`);
                    const data = await response.json();
                    const pagados = data.pagados || [];

                    mesesCheckboxes.forEach((checkbox, index) => {
                        const label = labels[index];
                        const icon = label.querySelector('.status-icon');
                        const mesValue = parseInt(checkbox.value);

                        // Reset clases
                        label.className = 'btn w-100 mes-label d-flex flex-column align-items-center py-2';
                        
                        if (pagados.includes(mesValue)) {
                            // Mes ya pagado
                            checkbox.disabled = true;
                            checkbox.checked = false;
                            label.classList.add('btn-success', 'text-white', 'opacity-75');
                            label.style.cursor = 'not-allowed';
                            icon.className = 'bi bi-check-circle-fill status-icon text-white';
                        } else {
                            // Mes disponible
                            checkbox.disabled = false;
                            label.style.cursor = 'pointer';
                            if (checkbox.checked) {
                                label.classList.add('btn-primary', 'text-white');
                                icon.className = 'bi bi-check-circle-fill status-icon';
                            } else {
                                label.classList.add('btn-outline-secondary');
                                icon.className = 'bi bi-circle status-icon';
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error al obtener los meses:', error);
                }
            } else {
                mesesContainer.style.display = 'none';
            }
        };

        // Escuchar cambios
        document.getElementById('socio_id').addEventListener('change', checkMesesPagados);
        anioInput.addEventListener('change', checkMesesPagados);
        
        // Ejecutar al cargar la pag (por si hay old values)
        checkMesesPagados();

        // Estilizar al hacer click en los meses disponibles
        mesesCheckboxes.forEach((checkbox, index) => {
            checkbox.addEventListener('change', () => {
                const label = labels[index];
                const icon = label.querySelector('.status-icon');
                if (checkbox.checked) {
                    label.classList.remove('btn-outline-secondary');
                    label.classList.add('btn-primary', 'text-white');
                    icon.className = 'bi bi-check-circle-fill status-icon';
                } else {
                    label.classList.remove('btn-primary', 'text-white');
                    label.classList.add('btn-outline-secondary');
                    icon.className = 'bi bi-circle status-icon';
                }
            });
        });

        // Botones rápidos
        const selectNextNMonths = (n) => {
            let selectedCount = 0;
            mesesCheckboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    if (selectedCount < n) {
                        checkbox.checked = true;
                        selectedCount++;
                    } else {
                        checkbox.checked = false;
                    }
                    // Disparar evento change para actualizar UI
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
        };

        document.getElementById('btn-3-meses').addEventListener('click', () => selectNextNMonths(3));
        document.getElementById('btn-6-meses').addEventListener('click', () => selectNextNMonths(6));
        document.getElementById('btn-12-meses').addEventListener('click', () => selectNextNMonths(12));
        
        document.getElementById('btn-limpiar').addEventListener('click', () => {
            mesesCheckboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = false;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
        });

        form.addEventListener('submit', (e) => {
            const checkedBoxes = document.querySelectorAll('.mes-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Debe seleccionar al menos un mes para registrar el pago.');
            }
        });
    </script>
</x-app-layout>