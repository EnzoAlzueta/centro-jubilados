<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Barrio
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
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

                    {{-- Campo Zona (Select para probar TomSelect) --}}
                    {{-- Este campo es solo visual para probar la librería --}}
                    <div class="mb-3">
                        <label for="zona" class="form-label">Zona (Ejemplo TomSelect)</label>
                        <select id="select-zona" name="zona" placeholder="Selecciona una zona...">
                            <option value="">Selecciona una zona...</option>
                            <option value="norte">Zona Norte</option>
                            <option value="sur">Zona Sur</option>
                            <option value="este">Zona Este</option>
                            <option value="oeste">Zona Oeste</option>
                        </select>
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