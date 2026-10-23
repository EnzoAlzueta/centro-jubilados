<x-app-layout>
    <div class="container-fluid px-4 px-md-5 mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <h1 class="h2 fw-bold">Crear usuario</h1>
                <p class="text-muted">Cree una cuenta para que otra persona pueda acceder al sistema.</p>

                @if(session('success'))
                    <div class="alert alert-success" role="status">{{ session('success') }}</div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary bg-opacity-10 border-0 p-4">
                        <h2 class="h5 text-primary fw-bold mb-0"><i class="bi bi-person-plus me-2" aria-hidden="true"></i>Datos de acceso</h2>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nombre completo</label>
                                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" maxlength="255" required autofocus autocomplete="name" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                                    @error('name')<div id="name-error" class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Correo electrónico</label>
                                    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" maxlength="255" required autocomplete="off" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                                    @error('email')<div id="email-error" class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" required minlength="8" autocomplete="new-password" aria-describedby="password-help @error('password') password-error @enderror" @error('password') aria-invalid="true" @enderror>
                                    <div id="password-help" class="form-text">Debe tener al menos 8 caracteres.</div>
                                    @error('password')<div id="password-error" class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required minlength="8" autocomplete="new-password">
                                </div>
                                <div class="col-12 mt-4">
                                    <h2 class="h6 fw-bold">Recuperación de cuenta</h2>
                                    <p class="text-muted small mb-0">Acuerde la pregunta y la respuesta con la persona que utilizará esta cuenta.</p>
                                </div>
                                <div class="col-12">
                                    <label for="security_question" class="form-label">Pregunta de seguridad</label>
                                    <input id="security_question" name="security_question" type="text" class="form-control @error('security_question') is-invalid @enderror" value="{{ old('security_question') }}" maxlength="255" required placeholder="Por ejemplo: ¿Cuál fue tu primera mascota?" @error('security_question') aria-invalid="true" aria-describedby="question-error" @enderror>
                                    @error('security_question')<div id="question-error" class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label for="security_answer" class="form-label">Respuesta de seguridad</label>
                                    <input id="security_answer" name="security_answer" type="password" class="form-control @error('security_answer') is-invalid @enderror" maxlength="255" required autocomplete="off" @error('security_answer') aria-invalid="true" aria-describedby="answer-error" @enderror>
                                    @error('security_answer')<div id="answer-error" class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4 pt-3 border-top">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Volver al inicio</a>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-person-plus me-2" aria-hidden="true"></i>Crear usuario</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
