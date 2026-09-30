<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('titulo', 'Renta de Maquinaria San Juan')
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #eaf4fc;
            font-family: Arial, Helvetica, sans-serif;
        }

        .contenedor-principal {
            display: flex;
            width: calc(100% - 80px);
            min-height: calc(100vh - 80px);
            margin: 40px;
            background: white;
            border: 7px solid #202522;
            border-radius: 25px;
            overflow: hidden;
        }

        /* SIDEBAR */

        .sidebar {
            width: 220px;
            min-width: 220px;
            background: #202522;
            color: white;
            padding: 25px 18px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
        }

        .logo-cuadro {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #f6f1dc;
            color: #202522;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }

        .logo-titulo {
            font-size: 17px;
            font-weight: bold;
        }

        .logo-subtitulo {
            font-size: 9px;
            color: #bfc4c1;
            margin-top: 3px;
        }

        .titulo-menu {
            font-size: 10px;
            letter-spacing: 1.5px;
            color: #aeb4b0;
            margin: 5px 10px 15px;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu li {
            margin-bottom: 4px;
        }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #d1d5d2;
            padding: 11px 10px;
            border-radius: 10px;
            font-size: 12px;
            transition: 0.2s;
        }

        .menu a:hover {
            background: #343b37;
            color: white;
        }

        .menu a.activo {
            background: #39413d;
            color: white;
            font-weight: bold;
        }

        .numero {
            width: 18px;
            font-size: 9px;
            color: #aab0ac;
        }

        .separador-menu {
            border-top: 1px solid #414743;
            margin: 20px 0;
        }

        .cerrar-sesion {
            margin-top: auto;
        }

        .cerrar-sesion button {
            width: 100%;
            background: transparent;
            border: none;
            color: white;
            text-align: left;
            padding: 10px;
            font-size: 12px;
        }

        .cerrar-sesion button:hover {
            background: #343b37;
            border-radius: 8px;
        }

        /* CONTENIDO */

        .contenido {
            flex: 1;
            min-width: 0;
            background: white;
            padding: 25px 28px;
            display: flex;
            flex-direction: column;
        }

        .encabezado {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 18px;
            margin-bottom: 25px;
            border-bottom: 1px solid #e1e5e2;
        }

        .nombre-sistema {
            font-size: 10px;
            letter-spacing: 1.2px;
            color: #68716c;
            margin-bottom: 5px;
        }

        .titulo-pagina {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .usuario {
            background: #f4f5f4;
            border-radius: 30px;
            padding: 8px 15px 8px 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .usuario-icono {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #dcefdc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #183a1d;
        }

        .usuario-nombre {
            font-size: 11px;
            font-weight: bold;
        }

        .usuario-cuenta {
            font-size: 9px;
            color: #777;
        }

        .contenido-vista {
            flex: 1;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #e1e5e2;
            padding-top: 15px;
            margin-top: 35px;
            font-size: 9px;
            color: #68716c;
        }

        /* TABLAS */

        .table-dark th {
            background: #252a2d !important;
            color: white !important;
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
        }

        /* RESPONSIVE */

        @media (max-width: 900px) {

            .contenedor-principal {
                width: 100%;
                margin: 0;
                border-radius: 0;
                border: none;
            }

            .sidebar {
                width: 190px;
                min-width: 190px;
            }

        }

    </style>

</head>


<body>

<div class="contenedor-principal">


    <!-- MENÚ LATERAL -->

    <aside class="sidebar">


        <!-- LOGO -->

        <div class="logo">

            <div class="logo-cuadro">
                SJ
            </div>

            <div>

                <div class="logo-titulo">
                    San Juan
                </div>

                <div class="logo-subtitulo">
                    RENTA DE MAQUINARIA
                </div>

            </div>

        </div>


        <div class="titulo-menu">
            ADMINISTRACIÓN
        </div>


        <ul class="menu">


            <!-- PERSONAS -->

            <li>
                <a
                    href="{{ route('personas.index') }}"
                    class="{{ request()->routeIs('personas.*') ? 'activo' : '' }}"
                >
                    <span class="numero">01</span>
                    Personas
                </a>
            </li>


            <!-- CLIENTES -->

            <li>
                <a
                    href="{{ route('clientes.index') }}"
                    class="{{ request()->routeIs('clientes.*') ? 'activo' : '' }}"
                >
                    <span class="numero">02</span>
                    Clientes
                </a>
            </li>


            <!-- DIRECCIONES -->

            <li>
                <a
                    href="{{ route('direcciones_clientes.index') }}"
                    class="{{ request()->routeIs('direcciones_clientes.*') ? 'activo' : '' }}"
                >
                    <span class="numero">03</span>
                    Direcciones de clientes
                </a>
            </li>


            <!-- PROVEEDORES -->

            <li>
                <a
                    href="{{ route('proveedores.index') }}"
                    class="{{ request()->routeIs('proveedores.*') ? 'activo' : '' }}"
                >
                    <span class="numero">04</span>
                    Proveedores
                </a>
            </li>


            <!-- MÁQUINAS -->

            <li>
                <a
                    href="{{ route('maquinas.index') }}"
                    class="{{ request()->routeIs('maquinas.*') ? 'activo' : '' }}"
                >
                    <span class="numero">05</span>
                    Máquinas
                </a>
            </li>


            <!-- COMPRAS -->

            <li>
                <a
                    href="{{ route('compras.index') }}"
                    class="{{ request()->routeIs('compras.*') ? 'activo' : '' }}"
                >
                    <span class="numero">06</span>
                    Compras
                </a>
            </li>


            <!-- TIPOS DE MÁQUINA -->

            <li>
                <a
                    href="{{ route('tipos_maquina.index') }}"
                    class="{{ request()->routeIs('tipos_maquina.*') ? 'activo' : '' }}"
                >
                    <span class="numero">07</span>
                    Tipos de máquina
                </a>
            </li>


            <!-- ESTADOS DE MÁQUINA -->

            <li>
                <a
                    href="{{ route('estados_maquina.index') }}"
                    class="{{ request()->routeIs('estados_maquina.*') ? 'activo' : '' }}"
                >
                    <span class="numero">08</span>
                    Estados de máquina
                </a>
            </li>


        </ul>


        <div class="separador-menu"></div>


        <div class="titulo-menu">
            CONFIGURACIÓN
        </div>


        <ul class="menu">

            <li>

                <a href="javascript:void(0);">

                    <span>
                        Catálogos
                    </span>

                    <span style="margin-left:auto;">
                        +
                    </span>

                </a>

            </li>

        </ul>


        <div class="cerrar-sesion">

            <div class="separador-menu"></div>

            <form
                action="{{ route('logout') }}"
                method="POST"
            >

                @csrf

                <button type="submit">
                    ↪ Cerrar sesión
                </button>

            </form>

        </div>


    </aside>


    <!-- CONTENIDO PRINCIPAL -->

    <main class="contenido">


        <!-- ENCABEZADO -->

        <header class="encabezado">

            <div>

                <div class="nombre-sistema">
                    RENTA DE MAQUINARIA SAN JUAN
                </div>

                <h1 class="titulo-pagina">
                    @yield('titulo', 'Panel')
                </h1>

            </div>


            <div class="usuario">

                <div class="usuario-icono">

                    @auth
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    @else
                        U
                    @endauth

                </div>


                <div>

                    <div class="usuario-nombre">

                        @auth
                            {{ Auth::user()->name ?? 'Usuario' }}
                        @else
                            Usuario
                        @endauth

                    </div>

                    <div class="usuario-cuenta">
                        Mi cuenta
                    </div>

                </div>

            </div>

        </header>


        <!-- MENSAJE DE ÉXITO -->

        @if(session('success'))

            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >

                {{ session('success') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        <!-- MENSAJE DE ERROR -->

        @if(session('error'))

            <div
                class="alert alert-danger alert-dismissible fade show"
                role="alert"
            >

                {{ session('error') }}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        @endif


        <!-- AQUÍ SE CARGAN LAS VISTAS -->

        <div class="contenido-vista">

            @yield('contenido')

        </div>


        <!-- PIE DE PÁGINA -->

        <footer class="footer">

            <div>
                San Juan · Gestión de maquinaria
            </div>

            <div>
                {{ date('Y') }}
            </div>

        </footer>


    </main>


</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>