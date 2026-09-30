@extends('layouts.template')

@section('titulo', 'Mantenimientos')

@section('contenido')

<style>
    .encabezado-modulo {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    .encabezado-modulo h2 {
        margin: 0 0 6px;
        font-size: 24px;
        font-weight: bold;
        color: var(--oscuro);
    }

    .encabezado-modulo p {
        margin: 0;
        color: var(--gris);
        font-size: 13px;
    }

    .boton-nuevo {
        padding: 11px 20px;
        border-radius: 25px;
        background: var(--oscuro);
        color: white;
        text-decoration: none;
        font-size: 13px;
        white-space: nowrap;
    }

    .boton-nuevo:hover {
        background: #3b453d;
        color: white;
    }

    .bloque {
        margin-bottom: 28px;
        padding: 24px;
        border-radius: 18px;
        border: 1px solid #e9ede9;
        background: white;
    }

    .bloque-crema {
        background: #f7f0da;
    }

    .titulo-bloque {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .titulo-bloque h3 {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
    }

    .contador {
        padding: 6px 12px;
        border-radius: 20px;
        background: #e1efdf;
        font-size: 11px;
    }

    .tabla-contenedor {
        overflow-x: auto;
    }

    .tabla-san-juan {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .tabla-san-juan th {
        padding: 13px 12px;
        background: var(--oscuro);
        color: white;
        font-size: 11px;
        font-weight: normal;
        text-align: left;
        white-space: nowrap;
    }

    .tabla-san-juan th:first-child {
        border-radius: 12px 0 0 12px;
    }

    .tabla-san-juan th:last-child {
        border-radius: 0 12px 12px 0;
    }

    .tabla-san-juan td {
        padding: 14px 12px;
        border-bottom: 1px solid #edf0ed;
        vertical-align: middle;
    }

    .boton-editar,
    .boton-eliminar {
        display: inline-block;
        padding: 7px 13px;
        border: 0;
        border-radius: 20px;
        font-size: 11px;
        text-decoration: none;
        cursor: pointer;
    }

    .boton-editar {
        background: var(--crema);
        color: var(--oscuro);
    }

    .boton-eliminar {
        background: #f4dddd;
        color: #742d2d;
    }

    .boton-editar:hover {
        color: var(--oscuro);
        background: #ece0bc;
    }

    .boton-eliminar:hover {
        background: #eccaca;
    }

    @media(max-width: 700px) {
        .encabezado-modulo,
        .titulo-bloque {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="encabezado-modulo">

    <div>
        <h2>Control de mantenimientos</h2>
        <p>
            Registro y seguimiento del mantenimiento de la maquinaria.
        </p>
    </div>

    <a href="{{ route('mantenimientos.create') }}"
       class="boton-nuevo">
        + Nuevo mantenimiento
    </a>

</div>


<div class="bloque">

    <div class="titulo-bloque">

        <h3>Mantenimientos registrados</h3>

        <span class="contador">
            {{ $mantenimientos->count() }} registros
        </span>

    </div>

    <div class="tabla-contenedor">

        <table class="tabla-san-juan">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Máquina</th>
                    <th>Tipo</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Costo</th>
                    <th>Descripción</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($mantenimientos as $mantenimiento)

                    <tr>

                        <td>
                            #{{ $mantenimiento->id_mantenimiento }}
                        </td>

                        <td>
                            {{ $mantenimiento->maquina_codigo ?? 'Sin código' }}
                        </td>

                        <td>
                            {{ $mantenimiento->tipo_nombre }}
                        </td>

                        <td>
                            {{ $mantenimiento->fecha_entrada }}
                        </td>

                        <td>
                            {{ $mantenimiento->fecha_salida ?? 'Pendiente' }}
                        </td>

                        <td>
                            ${{ number_format($mantenimiento->costo ?? 0, 2) }}
                        </td>

                        <td>
                            {{ $mantenimiento->descripcion }}
                        </td>

                        <td>
                            {{ $mantenimiento->observaciones }}
                        </td>

                        <td>

                            <a href="{{ route(
                                'mantenimientos.edit',
                                $mantenimiento->id_mantenimiento
                            ) }}"
                               class="boton-editar">
                                Editar
                            </a>

                            <form
                                action="{{ route(
                                    'mantenimientos.destroy',
                                    $mantenimiento->id_mantenimiento
                                ) }}"
                                method="POST"
                                style="display:inline"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="boton-eliminar"
                                    onclick="return confirm('¿Eliminar este mantenimiento?')"
                                >
                                    Borrar
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" style="text-align:center;">
                            No hay mantenimientos registrados.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<div class="bloque bloque-crema" id="tipos">

    <div class="titulo-bloque">

        <div>
            <h3>Tipos de mantenimiento</h3>
            <small>
                Catálogo utilizado para clasificar los mantenimientos.
            </small>
        </div>

        <a href="{{ route('tipos_mantenimiento.create') }}"
           class="boton-nuevo">
            + Nuevo tipo
        </a>

    </div>

    <div class="tabla-contenedor">

        <table class="tabla-san-juan">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($tipos as $tipo)

                    <tr>

                        <td>
                            #{{ $tipo->id_tipo_mantenimiento }}
                        </td>

                        <td>
                            <strong>{{ $tipo->nombre }}</strong>
                        </td>

                        <td>
                            {{ $tipo->descripcion }}
                        </td>

                        <td>

                            <a href="{{ route(
                                'tipos_mantenimiento.edit',
                                $tipo->id_tipo_mantenimiento
                            ) }}"
                               class="boton-editar">
                                Editar
                            </a>

                            <form
                                action="{{ route(
                                    'tipos_mantenimiento.destroy',
                                    $tipo->id_tipo_mantenimiento
                                ) }}"
                                method="POST"
                                style="display:inline"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="boton-eliminar"
                                    onclick="return confirm('¿Eliminar este tipo de mantenimiento?')"
                                >
                                    Borrar
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" style="text-align:center;">
                            No existen tipos de mantenimiento.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection