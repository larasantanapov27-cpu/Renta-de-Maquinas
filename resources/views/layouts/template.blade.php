<!DOCTYPE html>

<html lang="es">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">



    <title>@yield('titulo', 'Panel') | Maquinaria San Juan</title>



    {{-- Bootstrap local --}}

    <link rel="stylesheet"

          href="{{ asset('bootstrap/css/bootstrap.min.css') }}">



    <style>

        :root {

            --fondo: #e7f2fc;

            --oscuro: #202321;

            --crema: #f5eed8;

            --texto: #252925;

            --gris: #727a74;

        }



        * {

            box-sizing: border-box;

        }



        body {

            margin: 0;

            background: var(--fondo);

            color: var(--texto);

            font-family: Arial, Helvetica, sans-serif;

        }



        .contenedor-app {

            display: flex;

            min-height: calc(100vh - 40px);

            max-width: 1600px;

            margin: 20px auto;

            padding: 8px;

            background: var(--oscuro);

            border-radius: 28px;

            box-shadow: 0 20px 50px rgba(34, 52, 70, .15);

        }



        /* Menú lateral */

        .barra-lateral {

            width: 245px;

            flex-shrink: 0;

            padding: 22px 14px;

            color: #fff;

        }



        .marca {

            display: flex;

            align-items: center;

            gap: 12px;

            margin-bottom: 30px;

            padding: 0 8px;

        }



        .marca-logo {

            display: grid;

            place-items: center;

            width: 44px;

            height: 44px;

            flex-shrink: 0;

            border-radius: 13px;

            background: var(--crema);

            color: var(--oscuro);

            font-weight: bold;

            font-size: 17px;

        }



        .marca-nombre {

            font-size: 20px;

            font-weight: bold;

        }



        .marca-descripcion {

            display: block;

            margin-top: 4px;

            color: #bdc5be;

            font-size: 9px;

            letter-spacing: 1px;

        }



        .menu-titulo {

            margin: 24px 12px 10px;

            color: #acb7ae;

            font-size: 10px;

            letter-spacing: 1.5px;

        }



        .enlace-menu {

            display: flex;

            align-items: center;

            gap: 12px;

            width: 100%;

            margin-bottom: 5px;

            padding: 12px;

            border: 0;

            border-radius: 12px;

            background: transparent;

            color: #e1e6e2;

            font-size: 13px;

            text-align: left;

            text-decoration: none;

            transition: *background* .2s, color .2s;

        }



        a.enlace-menu:hover,

        button.enlace-menu:hover {

            background: #353c36;

            color: #fff;

        }



        .enlace-menu.activo {

            background: var(--crema);

            color: var(--oscuro);

            font-weight: bold;

        }



        .menu-numero {

            min-width: 22px;

            font-size: 10px;

            opacity: .75;

        }



        .enlace-menu.pendiente {

            color: #a5afa7;

            cursor: default;

        }



        .catalogos summary {

            cursor: pointer;

            list-style: none;

        }



        .catalogos summary::-webkit-details-marker {

            display: none;

        }



        .catalogos summary::after {

            content: "+";

            margin-left: auto;

        }



        .catalogos[open] summary::after {

            content: "−";

        }



        .submenu {

            margin-left: 12px;

            padding-left: 8px;

            border-left: 1px solid #465048;

        }



        .submenu .enlace-menu {

            font-size: 12px;

            padding: 10px;

        }



        .salir {

            margin-top: 25px;

            padding-top: 16px;

            border-top: 1px solid #414842;

        }



        .boton-menu {

            display: none;

        }



        /* Panel principal */

        .panel-principal {

            display: flex;

            flex: 1;

            flex-direction: column;

            min-width: 0;

            padding: 30px;

            border-radius: 22px;

            background: #fff;

        }



        .barra-superior {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            padding-bottom: 25px;

            border-bottom: 1px solid #edf0ed;

        }



        .subtitulo-panel {

            margin: 0 0 7px;

            color: var(--gris);

            font-size: 10px;

            letter-spacing: 1.5px;

        }



        .titulo-panel {

            margin: 0;

            font-size: 28px;

            font-weight: bold;

            letter-spacing: -.8px;

        }



        .usuario {

            display: flex;

            align-items: center;

            gap: 10px;

            max-width: 260px;

            padding: 7px 16px 7px 7px;

            border-radius: 30px;

            background: #f5f6f8;

        }



        .usuario-avatar {

            display: grid;

            place-items: center;

            width: 38px;

            height: 38px;

            flex-shrink: 0;

            border-radius: 50%;

            background: #dfefdf;

            font-weight: bold;

        }



        .usuario-nombre {

            font-size: 12px;

            font-weight: bold;

            overflow-wrap: anywhere;

        }



        .usuario-etiqueta {

            display: block;

            margin-top: 2px;

            color: var(--gris);

            font-size: 10px;

        }



        .contenido {

            flex: 1;

            min-width: 0;

            padding: 28px 0;

        }



        /* Clases reutilizables */

        .tarjeta-contenido {

            padding: 24px;

            border: 1px solid #e9ede9;

            border-radius: 18px;

            background: #fff;

        }



        .tarjeta-azul {

            background: #e2f0fc;

        }



        .tarjeta-verde {

            background: #e1efdf;

        }



        .tarjeta-morada {

            background: #ece3f5;

        }



        .tarjeta-crema {

            background: #f7f0da;

        }



        .boton-principal {

            display: inline-block;

            padding: 10px 20px;

            border: 0;

            border-radius: 25px;

            background: var(--oscuro);

            color: #fff;

            font-size: 13px;

            text-decoration: none;

        }



        .boton-principal:hover {

            background: #3b453d;

            color: #fff;

        }



        .pie-panel {

            display: flex;

            justify-content: space-between;

            gap: 15px;

            padding-top: 18px;

            border-top: 1px solid #edf0ed;

            color: var(--gris);

            font-size: 11px;

        }



        :focus-visible {

            outline: 3px solid #669aca;

            outline-offset: 3px;

        }



        @media (min-width: 992px) {

            .barra-lateral {

                position: sticky;

                top: 28px;

                align-self: flex-start;

                max-height: calc(100vh - 56px);

                overflow-y: auto;

                scrollbar-width: thin;

                scrollbar-color: #536057 transparent;

            }

        }



        @media (max-width: 1650px) {

            .contenedor-app {

                margin: 20px;

            }

        }



        @media (max-width: 991px) {

            .contenedor-app {

                display: block;

                margin: 12px;

                min-height: calc(100vh - 24px);

            }



            .barra-lateral {

                width: 100%;

                padding: 14px;

            }



            .marca {

                margin-bottom: 16px;

            }



            .boton-menu {

                display: block;

                width: 100%;

                padding: 10px 14px;

                border: 1px solid #566258;

                border-radius: 10px;

                background: #343b35;

                color: #fff;

                text-align: left;

            }



            .navegacion {

                display: none;

                padding-top: 12px;

            }



            .navegacion.abierta {

                display: block;

            }



            .panel-principal {

                min-height: 75vh;

                padding: 22px;

            }

        }



        @media (max-width: 575px) {

            .barra-superior {

                align-items: flex-start;

                flex-direction: column;

                gap: 16px;

            }



            .titulo-panel {

                font-size: 25px;

            }



            .panel-principal {

                padding: 20px 16px;

            }



            .tarjeta-contenido {

                padding: 18px;

            }



            .pie-panel {

                flex-direction: column;

                gap: 5px;

            }

        }

    </style>



    @stack('styles')

</head>



<body>



    @php



        /*

         * Módulos principales.

         * El orden de este arreglo determina

         * la numeración del menú.

         */



        $modulos = [

            ['texto' => 'Personas',          'ruta' => 'personas.index'],

            ['texto' => 'Clientes',          'ruta' => 'clientes.index'],

            ['texto' => 'Direcciones de clientes', 'ruta' => 'direcciones_clientes.index'],

            ['texto' => 'Proveedores',       'ruta' => 'proveedores.index'],

            ['texto' => 'Rentas',            'ruta' => 'rentas.index'],

            ['texto' => 'Máquinas',          'ruta' => 'maquinas.index'],

            ['texto' => 'Pagos',             'ruta' => 'pagos.index'],

            ['texto' => 'Compras',           'ruta' => 'compras.index'],

            ['texto' => 'Mantenimientos',    'ruta' => 'mantenimientos.index'],

            ['texto' => 'Bajas de máquinas', 'ruta' => 'bajas_maquinas.index'],

            ['texto' => 'Detalle tarifa',    'ruta' => 'detalle_tarifa.index'],

        ];





        /*

         * Catálogos

         */



        $catalogos = [

            ['texto' => 'Tipos de máquina', 'ruta' => 'tipos_maquina.index'],

            ['texto' => 'Estados de máquina', 'ruta' => 'estados_maquina.index'],

            ['texto' => 'Estados de renta', 'ruta' => 'estados_renta.index'],

            ['texto' => 'Períodos de renta', 'ruta' => 'periodos_renta.index'],

            ['texto' => 'Métodos de pago', 'ruta' => 'metodos_pago.index'],

            ['texto' => 'Tipos de mantenimiento', 'ruta' => 'tipos_mantenimiento.index'],

            ['texto' => 'Motivos de baja', 'ruta' => 'motivos_baja.index'],

        ];





        /*

         * Comprueba si actualmente estamos

         * dentro de una ruta de catálogos.

         */



        $catalogoActivo = false;



        foreach ($catalogos as $catalogo) {



            $patron = str_replace(

                '.index',

                '.*',

                $catalogo['ruta']

            );



            if (request()->routeIs($patron)) {

                $catalogoActivo = true;

                break;

            }

        }





        /*

         * Nombre del usuario autenticado.

         */



        $nombreUsuario = auth()->user()?->name ?? 'Usuario';



    @endphp





    <div class="contenedor-app">



        <!-- MENÚ LATERAL -->

        <aside class="barra-lateral">



            <!-- LOGO -->

            <div class="marca">



                <span class="marca-logo" aria-hidden="true">

                    SJ

                </span>



                <div>



                    <span class="marca-nombre">

                        San Juan

                    </span>



                    <span class="marca-descripcion">

                        RENTA DE MAQUINARIA

                    </span>



                </div>



            </div>





            <!-- BOTÓN MENÚ RESPONSIVE -->

            <button

                type="button"

                class="boton-menu"

                id="botonMenu"

                aria-controls="menuPrincipal"

                aria-expanded="false"

            >

                ☰ Menú de navegación

            </button>





            <!-- NAVEGACIÓN -->

            <nav

                class="navegacion"

                id="menuPrincipal"

                aria-label="Menú principal"

            >



                <p class="menu-titulo">

                    ADMINISTRACIÓN

                </p>





                <!-- MÓDULOS PRINCIPALES -->

                @foreach ($modulos as $modulo)



                    @php



                        /*

                         * Comprueba si la ruta existe.

                         */



                        $existe = \Illuminate\Support\Facades\Route::has(

                            $modulo['ruta']

                        );





                        /*

                         * Comprueba si el módulo

                         * está actualmente seleccionado.

                         */



                        $activo = request()->routeIs(

                            str_replace(

                                '.index',

                                '.*',

                                $modulo['ruta']

                            )

                        );



                    @endphp





                    @if ($existe)



                        <a

                            href="{{ route($modulo['ruta']) }}"

                            class="enlace-menu {{ $activo ? 'activo' : '' }}"

                            @if ($activo)

                                aria-current="page"

                            @endif

                        >



                            <span

                                class="menu-numero"

                                aria-hidden="true"

                            >

                                {{ str_pad(

                                    $loop->iteration,

                                    2,

                                    '0',

                                    STR_PAD_LEFT

                                ) }}

                            </span>



                            {{ $modulo['texto'] }}



                        </a>



                    @else



                        <span

                            class="enlace-menu pendiente"

                            aria-disabled="true"

                            title="Módulo pendiente de habilitar"

                        >



                            <span

                                class="menu-numero"

                                aria-hidden="true"

                            >

                                {{ str_pad(

                                    $loop->iteration,

                                    2,

                                    '0',

                                    STR_PAD_LEFT

                                ) }}

                            </span>



                            {{ $modulo['texto'] }}



                        </span>



                    @endif



                @endforeach





                <!-- CONFIGURACIÓN -->

                <p class="menu-titulo">

                    CONFIGURACIÓN

                </p>





                <!-- CATÁLOGOS -->

                <details

                    class="catalogos"

                    @if ($catalogoActivo)

                        open

                    @endif

                >



                    <summary class="enlace-menu">

                        Catálogos

                    </summary>





                    <div class="submenu">



                        @foreach ($catalogos as $catalogo)



                            @php



                                $existe = \Illuminate\Support\Facades\Route::has(

                                    $catalogo['ruta']

                                );



                                $activo = request()->routeIs(

                                    str_replace(

                                        '.index',

                                        '.*',

                                        $catalogo['ruta']

                                    )

                                );



                            @endphp





                            @if ($existe)



                                <a

                                    href="{{ route($catalogo['ruta']) }}"

                                    class="enlace-menu {{ $activo ? 'activo' : '' }}"

                                    @if ($activo)

                                        aria-current="page"

                                    @endif

                                >



                                    {{ $catalogo['texto'] }}



                                </a>



                            @else



                                <span

                                    class="enlace-menu pendiente"

                                    aria-disabled="true"

                                    title="Catálogo pendiente de habilitar"

                                >



                                    {{ $catalogo['texto'] }}



                                </span>



                            @endif



                        @endforeach



                    </div>



                </details>





                <!-- CERRAR SESIÓN -->

                @if (\Illuminate\Support\Facades\Route::has('logout'))



                    <form

                        action="{{ route('logout') }}"

                        method="POST"

                        class="salir"

                    >



                        @csrf



                        <button

                            type="submit"

                            class="enlace-menu"

                        >

                            ↪ Cerrar sesión

                        </button>



                    </form>



                @endif



            </nav>



        </aside>





        <!-- PANEL PRINCIPAL -->

        <main class="panel-principal">



            <!-- ENCABEZADO -->

            <header class="barra-superior">



                <div>



                    <p class="subtitulo-panel">

                        RENTA DE MAQUINARIA SAN JUAN

                    </p>



                    <h1 class="titulo-panel">

                        @yield('titulo', 'Panel de administración')

                    </h1>



                </div>





                <!-- USUARIO -->

                <div class="usuario">



                    <span

                        class="usuario-avatar"

                        aria-hidden="true"

                    >

                        {{ mb_strtoupper(

                            mb_substr($nombreUsuario, 0, 1)

                        ) }}

                    </span>



                    <div>



                        <span class="usuario-nombre">

                            {{ $nombreUsuario }}

                        </span>



                        <span class="usuario-etiqueta">

                            Mi cuenta

                        </span>



                    </div>



                </div>



            </header>





            <!-- CONTENIDO -->

            <section

                class="contenido"

                aria-label="Contenido de la página"

            >



                <!-- MENSAJE DE ÉXITO -->

                @if (session('success'))



                    <div

                        class="alert alert-success"

                        role="status"

                    >

                        {{ session('success') }}

                    </div>



                @endif





                <!-- MENSAJE DE ERROR -->

                @if (session('error'))



                    <div

                        class="alert alert-danger"

                        role="alert"

                    >

                        {{ session('error') }}

                    </div>



                @endif





                <!-- ERRORES DE VALIDACIÓN -->

                @if ($errors->any())



                    <div

                        class="alert alert-danger"

                        role="alert"

                    >



                        <strong>

                            Revisa la información:

                        </strong>



                        <ul class="mb-0 mt-2">



                            @foreach ($errors->all() as $error)



                                <li>

                                    {{ $error }}

                                </li>



                            @endforeach



                        </ul>



                    </div>



                @endif





                <!-- CONTENIDO DE CADA VISTA -->

                @yield('contenido')



            </section>





            <!-- PIE -->

            <footer class="pie-panel">



                <span>

                    San Juan · Gestión de maquinaria

                </span>



                <span>

                    {{ date('Y') }}

                </span>



            </footer>



        </main>



    </div>





    {{-- Bootstrap local --}}

    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>





    <script>



        const botonMenu =

            document.getElementById('botonMenu');



        const menuPrincipal =

            document.getElementById('menuPrincipal');





        /*

         * Abrir y cerrar menú en dispositivos pequeños.

         */



        botonMenu.addEventListener('click', () => {



            const abierto =

                menuPrincipal.classList.toggle('abierta');



            botonMenu.setAttribute(

                'aria-expanded',

                String(abierto)

            );



        });





        /*

         * Cerrar menú utilizando Escape.

         */



        document.addEventListener('keydown', (event) => {



            if (

                event.key === 'Escape' &&

                menuPrincipal.classList.contains('abierta')

            ) {



                menuPrincipal.classList.remove('abierta');



                botonMenu.setAttribute(

                    'aria-expanded',

                    'false'

                );



                botonMenu.focus();



            }



        });



    </script>



    @stack('scripts')



</body>



</html>