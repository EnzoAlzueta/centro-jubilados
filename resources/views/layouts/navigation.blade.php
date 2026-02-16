<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- NAVEGACION BOOTSTRAP -->

    <nav class="navbar navbar-expand-lg bg-body-bg">
        <div class="container-fluid">
            <div>
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo CentroGestion" class="img-fluid"
                        style="width: 80px; height: auto;">
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('dashboard') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('socios.index') }}" class="nav-link">Socios</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('barrios.index') }}" class="nav-link">Barrios</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Alquileres
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('alquileres.index') }}">Gestión de
                                    Alquileres</a></li>
                            <li><a class="dropdown-item" href="{{ route('utilerias.index') }}">Gestión de Utilería</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('caja.index') }}" class="nav-link">Caja</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reportes.index') }}" class="nav-link">Reportes</a>
                    </li>

                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Editar perfil</a></li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <li><a class="dropdown-item" href="route('logout')" onclick="event.preventDefault();
                                        this.closest('form').submit();">Cerrar Sesion</a></li>
                            </form>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</nav>