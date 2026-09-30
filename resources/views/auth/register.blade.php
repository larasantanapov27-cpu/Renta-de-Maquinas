<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Crear cuenta | Maquinaria San Juan</title>

    <link rel="stylesheet"
          href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

    <style>
        body {
            margin: 0;
            background: #e5f1fc;
            color: #202120;
            font-family: Arial, Helvetica, sans-serif;
        }

        .register-shell {
            width: 100%;
            max-width: 650px;
            padding: 10px;
            border-radius: 28px;
            background: #202120;
            box-shadow: 0 25px 60px rgba(25, 40, 60, .18);
        }

        .register-header {
            padding: 20px;
            color: white;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            background: #f5efd9;
            color: #202120;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .register-content {
            padding: clamp(24px, 5vw, 45px);
            background: white;
            border-radius: 21px;
        }

        .register-label {
            display: inline-block;
            padding: 7px 13px;
            background: #e7def2;
            border-radius: 25px;
            font-size: 12px;
            font-weight: bold;
        }

        .form-control {
            min-height: 49px;
            border-radius: 12px;
            border-color: #dce1e6;
            background: #f7f8fa;
        }

        .form-control:focus {
            background: white;
            border-color: #779aba;
            box-shadow: 0 0 0 .2rem rgba(119, 154, 186, .18);
        }

        .btn-register {
            min-height: 50px;
            border-radius: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <main class="container min-vh-100 d-flex align-items-center
                 justify-content-center py-4">

        <div class="register-shell">
            <header class="register-header d-flex align-items-center gap-3">
                <div class="brand-logo" aria-hidden="true">SJ</div>

                <div>
                    <strong class="d-block">SAN JUAN</strong>
                    <small class="text-white-50">Renta de maquinaria</small>
                </div>
            </header>

            <section class="register-content">
                <span class="register-label mb-3">NUEVA CUENTA</span>

                <h1 class="h2 fw-bold">Crear cuenta</h1>

                <p class="text-secondary mb-4">
                    Completa tus datos para registrarte en el sistema.
                </p>

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Nombre completo
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Escribe tu nombre"
                            autocomplete="name"
                            minlength="2"
                            maxlength="100"
                            aria-describedby="nameError"
                            required
                            autofocus
                        >

                        @error('name')
                            <div id="nameError" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="nombre@ejemplo.com"
                            autocomplete="username"
                            maxlength="255"
                            aria-describedby="emailError"
                            required
                        >

                        @error('email')
                            <div id="emailError" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">
                            Contraseña
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            autocomplete="new-password"
                            minlength="12"
                            maxlength="64"
                            aria-describedby="passwordHelp passwordError"
                            required
                        >

                        <div id="passwordHelp" class="form-text">
                            Usa entre 12 y 64 caracteres. Puedes utilizar
                            una frase larga y fácil de recordar.
                        </div>

                        @error('password')
                            <div id="passwordError" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation"
                               class="form-label fw-semibold">
                            Confirmar contraseña
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            autocomplete="new-password"
                            minlength="12"
                            maxlength="64"
                            aria-describedby="confirmationError"
                            required
                        >

                        @error('password_confirmation')
                            <div id="confirmationError" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input
                            type="checkbox"
                            id="showPasswords"
                            class="form-check-input"
                            aria-controls="password password_confirmation"
                        >

                        <label for="showPasswords" class="form-check-label">
                            Mostrar contraseñas
                        </label>
                    </div>

                    <button type="submit"
                            class="btn btn-dark btn-register w-100">
                        Crear mi cuenta
                    </button>
                </form>

                <p class="text-secondary small text-center mt-4 mb-0">
                    ¿Ya tienes una cuenta?

                    <a href="{{ route('login') }}" class="text-dark fw-bold">
                        Iniciar sesión
                    </a>
                </p>
            </section>
        </div>
    </main>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        const password = document.getElementById('password');
        const confirmation = document.getElementById('password_confirmation');

        function validateConfirmation() {
            const different = confirmation.value !== ''
                && password.value !== confirmation.value;

            confirmation.setCustomValidity(
                different ? 'Las contraseñas no coinciden.' : ''
            );
        }

        password.addEventListener('input', validateConfirmation);
        confirmation.addEventListener('input', validateConfirmation);

        document.getElementById('showPasswords')
            .addEventListener('change', function () {
                const type = this.checked ? 'text' : 'password';

                password.type = type;
                confirmation.type = type;
            });
    </script>
</body>
</html>