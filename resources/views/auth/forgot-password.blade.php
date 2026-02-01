<x-guest-layout>

<!-- Validaciones para el botón de inicio -->
@if(session('status'))
    <div class="position-fixed top-0 start-50 translate-middle mt-5" style="z-index: 9999; min-width: 300px;">
        <div class="alert alert-primary alert-dismissible fade show shadow-lg" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>
                    {{ session('status') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if ($errors->has('email'))
    <div class="position-fixed top-0 start-50 translate-middle mt-5" style="z-index: 9999; min-width: 300px;">
        <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>
                    {{ $errors->first('email') }}                
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

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
                    <p class="text-body-secondary small" style="font-size: 20px;">Recuperar contraseña</p>
                </h3>
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf 
                <div class="mb-3">
                    <label for="email" :value="__('Email')"  class="form-labels small fw-bold text-secondary">Ingrese correo electronico</label>
                    <input type="text" name="email" :value="old('email')" required autofocus autocomplete="username" class="form-control form-control-lg fs-6" sytle="border-color: #dee2e6;">
                    
                   
                    
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm" style="background-color: #2563eb; border:none,; border-radius: 6px;">Enviar</button>
            
             
            </form>

        </div>
    </div>

<!-- 
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>


    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form> -->
</x-guest-layout>
