<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | Maquinaria San Juan</title>

    <link rel="stylesheet"
          href="{{ asset('bootstrap/css/bootstrap.min.css') }}">

    <style>
        body {
            margin: 0;
            background: #e5f1fc;
            color: #202120;
            font-family: Arial, Helvetica, sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 1050px;
            padding: 10px;
            background: #202120;
            border-radius: 30px;
            box-shadow: 0 25px 60px rgba(25, 40, 60, .20);
        }

        .presentation {
            height: 100%;
            padding: 40px 30px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand-logo {
            width: 54px;
            height: 54px;
            background: #f5efd9;
            color: #202120;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .presentation h2 {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: bold;
            letter-spacing: -1px;
            line-height: 1.15;
        }

        .presentation-description {
            color: #cbd0d4;
            line-height: 1.7;
        }

        .feature {
            padding: 17px;
            border-radius: 17px;
            color: #202120;
            height: 100%;
        }

        .feature small {
            display: block;
            margin-top: 5px;
        }

        .blue { background: #dceffc; }
        .green { background: #d9eedc; }
        .purple { background: #e7def2; }

        .login-form {
            min-height: 590px;
            padding: clamp(25px, 5vw, 60px);
            border-radius: 23px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .access-label {
            align-self: flex-start;
            padding: 8px 14px;
            border-radius: 25px;
            background: #f5efd9;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: .5px;
        }

        .form-control {
            min-height: 50px;
            border-radius: 12px;
            border-color: #dce1e6;
            background-color: #f7f8fa;
        }

        .form-control:focus {
            background-color: white;
            border-color: #779aba;
            box-shadow: 0 0 0 .2rem rgba(119, 154, 186, .18);
        }

        .btn-login {
            min-height: 50px;
            border-radius: 12px;
            font-weight: bold;
        }

        @media (max-width: 767px) {
            .presentation {
                padding: 25px 20px;
            }

            .login-form {
                min-height: auto;
            }
        }
    </style>
</head>

<body>
    <main class="container min-vh-100 d-flex align-items-center
                 justify-content-center py-4">

        <div class="login-container">
            <div class="row g-0">

                {{-- Presentación de la empresa --}}
                <div class="col-md-5">
                    <section class="presentation" aria-label="Presentación">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-5">
                                <div class="brand-logo" aria-hidden="true">
                                    SJ
                                </div>

                                <div>
                                    <strong class="d-block">SAN JUAN</strong>
                                    <small class="text-white-50">
                                        Renta de maquinaria
                                    </small>
                                </div>
                            </div>

                            <h2 class="mb-3">
                                Tu operación,<br>
                                bajo control.
                            </h2>

                            <p class="presentation-description mb-4">
                                Administra tus máquinas, organiza las rentas
                                y da seguimiento a cada movimiento.
                            </p>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <div class="feature blue">
                                    <strong>Inventario</strong>
                                    <small>Control de maquinaria.</small>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="feature green">
                                    <strong>Rentas</strong>
                                    <small>Seguimiento de servicios.</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="feature purple">
                                    <strong>Todo en un solo lugar</strong>
                                    <small>
                                        Clientes, pagos y mantenimiento.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- Formulario de acceso --}}
                <div class="col-md-7">
                    <section class="login-form">
                        <span class="access-label mb-4">
                        </span>

                        <h1 class="h2 fw-bold mb-2">
                            Bienvenido de nuevo
                        </h1>

                        <p class="text-secondary mb-4">
                            Ingresa tus datos para continuar.
                        </p>

                        {{-- Confirmación después de crear una cuenta --}}
                        @if (session('success'))
                            <div class="alert alert-success" role="status">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Errores enviados por el controlador --}}
                        @if ($errors->any())
                            <div id="loginErrors"
                                 class="alert alert-danger"
                                 role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('login.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">
                                    Correo electrónico
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="nombre@ejemplo.com"
                                    autocomplete="username"
                                    maxlength="255"
                                    @error('email')
                                        aria-invalid="true"
                                        aria-describedby="loginErrors"
                                    @enderror
                                    required
                                    autofocus
                                >
                            </div>

                            <div class="mb-3">
                                <label for="password"
                                       class="form-label fw-semibold">
                                    Contraseña
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Escribe tu contraseña"
                                    autocomplete="current-password"
                                    @error('password')
                                        aria-invalid="true"
                                        aria-describedby="loginErrors"
                                    @enderror
                                    required
                                >
                            </div>

                            <div class="form-check mb-4">
                                <input
                                    type="checkbox"
                                    id="showPassword"
                                    class="form-check-input"
                                    aria-controls="password"
                                >

                                <label for="showPassword"
                                       class="form-check-label text-secondary">
                                    Mostrar contraseña
                                </label>
                            </div>

                            <button type="submit"
                                    class="btn btn-dark btn-login w-100">
                                Iniciar sesión
                            </button>
                        </form>

                        <p class="small text-secondary mt-4 mb-0 text-center">
                            ¿No tienes una cuenta?

                            <a href="{{ route('register') }}"
                               class="fw-bold text-dark">
                                Crear cuenta
                            </a>
                        </p>

                       
                    </section>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        document.getElementById('showPassword')
            .addEventListener('change', function () {
                document.getElementById('password').type =
                    this.checked ? 'text' : 'password';
            });
    </script>
</body>
</html>