<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Barrio
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('barrios.update', $barrio->id) }}" method="POST" id="form-editar-barrio">
                    @csrf
                    @method('PUT')

                    {{-- Campo Nombre (Input normal de Bootstrap) --}}
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Barrio</label>
                        <input type="text" name="nombre" id="nombre" class="form-control"
                            value="{{ old('nombre', $barrio->nombre) }}" required>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Actualizar Barrio</button>
                        <a href="{{ route('barrios.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>