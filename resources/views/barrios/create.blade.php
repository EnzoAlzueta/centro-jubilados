<x-app-layout>
   <div class="container-fluid px-4 px-md-5 mt-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Ingresar Nuevo Barrio</h2>
            <a href="{{ route('barrios.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                    
                <form action="{{ route('barrios.store') }}" method="POST" id="form-crear-barrio">
                    @csrf 

                    {{-- Campo Nombre (Input normal de Bootstrap) --}}
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Barrio</label>
                        <input type="text" 
                                name="nombre" 
                                id="nombre" 
                                class="form-control" 
                                placeholder="Ej: San Vicente" 
                                required>
                    </div>
                    {{-- Botones de acción --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">Guardar Barrio</button>
                        <a href="{{ route('barrios.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Script para activar TomSelect --}}
    <script type="module">
        new TomSelect("#select-zona", {
            create: true, // Permite al usuario escribir una opción nueva si no existe
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    </script>
</x-app-layout>