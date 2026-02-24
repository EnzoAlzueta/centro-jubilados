<x-guest-layout>

    @if($errors->any())
    <div class="position-fixed top-0 start-50 translate-middle mt-5" style="z-index: 9999; min-width: 300px;">
        <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <div class="container d-flex justify-content-center align-items-center vh-100 px-3">
        <div class="card-shadow border-0 p-4"
            style="width: 35%; max-width: 420px; border-radius:12px; background-color: #ffffff; box-shadow: 5px 5px 10px rgba(0,0,0,0.5);">
            <div class="card-body text-center">
                <div>
                    <a href="/login">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo CentroGestion" class="img-fluid"
                            style="width: 150px; height: auto;">
                    </a>
                </div>
                <h3 class="fw-bold text-dark mb-4" style="letter-spacing: -0.5px;">
                    Centro Gestion
                    <p class="text-body-secondary small" style="font-size: 20px;">Restablecer contraseña</p>
                </h3>
            </div>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label small fw-bold text-secondary">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required
                        autofocus autocomplete="username" class="form-control form-control-lg fs-6"
                        style="border-color: #dee2e6;">
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label small fw-bold text-secondary">Nueva contraseña</label>
                    <input type="password" id="password" name="password" required autocomplete="new-password"
                        class="form-control form-control-lg fs-6" style="border-color: #dee2e6;">
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label small fw-bold text-secondary">Confirmar
                        contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        autocomplete="new-password" class="form-control form-control-lg fs-6"
                        style="border-color: #dee2e6;">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm"
                    style="background-color: #2563eb; border:none; border-radius: 6px;">
                    Restablecer contraseña
                </button>
            </form>
        </div>
    </div>

</x-guest-layout>