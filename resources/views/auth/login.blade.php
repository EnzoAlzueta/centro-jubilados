<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="container d-flex justify-content-center align-items-center vh-100 px-3">
        <div class="card-shadow border-0 p-4" style="width: 35%; max-width=420px; border-radius:12px; background-color: #ffffffff; box-shadow: 5px 5px 10px rgba(0,0,0,0.5);">
            <div class="card-body text-center">
                <div>
                    <a href="/login">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo CentroGestion" class="img-fluid" style="width: 150px; height: auto;">
                    </a>
                </div>
                <h3 class="fw-bold text-dark mb-4" style="latter-spacing: -0.5px;">
                    Centro Gestion
                    <p class="text-body-secondary small" style="font-size: 20px;">Iniciar sesión en su cuenta</p>
                </h3>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf 
                <div class="mb-3">
                    <label for="email" :value="__('Email')"  class="form-labels small fw-bold text-secondary">Nombre de usuario</label>
                    <input type="text" name="email" :value="old('email')" required autofocus autocomplete="username" class="form-control form-control-lg fs-6" sytle="border-color: #dee2e6;">
                </div>
                <div class="mb-2">
                    <label  for="password" :value="__('Password')" class="form-label small fw-bold text-secondary">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control form-control-lg fs-6" autocomplete="current-password" style="border-color: #dee2e6;">
                </div>
                @if (Route::has('password.request'))
                    <p class="text-muted small text-end">
                        <a href="{{ route('password.request') }}">¿Olvidó contraseña?</a>
                    </p>
                @endif
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm" style="background-color: #2563eb; border:none,; border-radius: 6px;">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</x-guest-layout>
