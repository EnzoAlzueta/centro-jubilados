<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center vh-100 px-3">
        <div class="card-shadow border-0 p-4"
            style="width: 35%; max-width: 420px; border-radius:12px; background-color: #ffffffff; box-shadow: 5px 5px 10px rgba(0,0,0,0.5);">
            <div class="card-body text-center">
                <div>
                    <a href="/login">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo CentroGestion" class="img-fluid"
                            style="width: 150px; height: auto;">
                    </a>
                </div>
                <h3 class="fw-bold text-dark mb-4" style="letter-spacing: -0.5px;">
                    Centro Gestion
                    <p class="text-body-secondary small" style="font-size: 20px;">Pregunta de Seguridad</p>
                </h3>
            </div>

            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Por favor, responde a tu pregunta de seguridad para restablecer tu contraseña.') }}
            </div>

            <form method="POST" action="{{ route('password.security-question.store') }}">
                @csrf

                <input type="hidden" name="email" value="{{ $email }}">

                <!-- Security Question -->
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Tu Pregunta de Seguridad</label>
                    <p class="fw-semibold text-dark p-2 bg-light rounded" style="border: 1px solid #dee2e6;">
                        {{ $security_question }}
                    </p>
                </div>

                <!-- Security Answer -->
                <div class="mb-4">
                    <label for="security_answer" class="form-label small fw-bold text-secondary">Tu Respuesta</label>
                    <input type="password" name="security_answer" id="security_answer" required autofocus
                        class="form-control form-control-lg fs-6" style="border-color: #dee2e6;">
                    @error('security_answer')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm"
                    style="background-color: #2563eb; border:none; border-radius: 6px;">
                    {{ __('Verificar Respuesta') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>