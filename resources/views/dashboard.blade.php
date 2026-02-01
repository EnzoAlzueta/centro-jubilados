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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
