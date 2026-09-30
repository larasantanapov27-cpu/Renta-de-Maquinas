@extends('layouts.template')

@section('contenido')

<style>
    .contenido-bajas {
        padding: 35px;
        color: #222522;
    }

    .encabezado-pagina {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .encabezado-pagina h1 {
        margin: 0;
        font-size: 32px;
        font-weight: 600;
        color: #222522;
    }

    .encabezado-pagina p {
        margin-top: 7px;
        color: #747a75;
    }

    .boton-nuevo {
        background: #222522;
        color: white;
        padding: 12px 22px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        border: none;
        cursor: pointer;
    }

    .boton-nuevo:hover {
        background: #3a3e3a;
        color: white;
    }

    .seccion {
        background: white;
        border-radius: 14px;
        padding: 25px;
        margin-bottom: 35px;
        box-shadow: 0 3px 12px rgba(0,0,0,.08);
    }

    .seccion-titulo {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .seccion-titulo h2 {
        margin: 0;
        font-size: 22px;
        color: #222522;
    }

    .seccion-titulo small {
        color: #858b86;
    }

    .tabla-contenedor {
        overflow-x: auto;
    }

    .tabla-bajas {
        width: 100%;
        border-collapse: collapse;
    }

    .tabla-bajas th {
        text-align: left;
        padding: 14px;
        background: #222522;
        color: #d8ddd8;
        font-weight: 500;
    }

    .tabla-bajas td {
        padding: 14px;
        border-bottom: 1px solid #e3e5e3;
        color: #4e534f;
    }

    .tabla-bajas tr:hover td {
        background: #f5f6f5;
    }

    .acciones {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    /* BOTÓN EDITAR - AMARILLO */
    .btn-editar {
        padding: 7px 13px;
        background: #f8e8a8;
        color: #665515;
        border-radius: 6px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        display: inline-block;
    }

    .btn-editar:hover {
        background: #f1da7d;
        color: #51420e;
    }

    /* BOTÓN ELIMINAR - ROSITA */
    .btn-eliminar {
        padding: 7px 13px;
        background: #f4c7d3;
        color: #7a3347;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-eliminar:hover {
        background: #eba9bb;
        color: #642638;
    }

    .acciones form {
        margin: 0;
    }

    .sin-registros {
        text-align: center;
        padding: 30px !important;
        color: #929792 !important;
    }
</style>


<div class="contenido-bajas">

    <div class="encabezado-pagina">

        <div>
            <h1>Bajas de máquinas</h1>
            <p>Administración de máquinas dadas de baja.</p>
        </div>

        <a href="{{ route('bajas_maquinas.create') }}"
           class="boton-nuevo">
            + Nueva baja
        </a>

    </div>


    {{-- ========================= --}}
    {{-- BAJAS DE MÁQUINAS --}}
    {{-- ========================= --}}

    <div class="seccion">

        <div class="seccion-titulo">

            <div>
                <h2>Máquinas dadas de baja</h2>

                <small>
                    Registro de bajas realizadas.
                </small>
            </div>

        </div>

        <div class="tabla-contenedor">

            <table class="tabla-bajas">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Máquina</th>
                        <th>Motivo</th>
                        <th>Fecha de baja</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($bajas as $baja)

                        <tr>

                            <td>
                                #{{ $baja->id_baja }}
                            </td>

                            <td>
                                {{ $baja->maquina_codigo ?? 'Sin máquina' }}
                            </td>

                            <td>
                                {{ $baja->motivo_nombre ?? 'Sin motivo' }}
                            </td>

                            <td>
                                {{ $baja->fecha_baja }}
                            </td>

                            <td>
                                {{ $baja->descripcion ?? 'Sin descripción' }}
                            </td>

                            <td>

                                <div class="acciones">

                                    <a href="{{ route('bajas_maquinas.edit', $baja->id_baja) }}"
                                       class="btn-editar">
                                        Editar
                                    </a>

                                    <form action="{{ route('bajas_maquinas.destroy', $baja->id_baja) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn-eliminar"
                                                onclick="return confirm('¿Eliminar esta baja?')">
                                            Eliminar
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6"
                                class="sin-registros">
                                No hay bajas registradas.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ========================= --}}
    {{-- MOTIVOS DE BAJA --}}
    {{-- ========================= --}}

    <div class="seccion">

        <div class="seccion-titulo">

            <div>

                <h2>Motivos de baja</h2>

                <small>
                    Catálogo utilizado para clasificar las bajas.
                </small>

            </div>

            <a href="{{ route('motivos_baja.create') }}"
               class="boton-nuevo">
                + Nuevo motivo
            </a>

        </div>


        <div class="tabla-contenedor">

            <table class="tabla-bajas">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($motivos as $motivo)

                        <tr>

                            <td>
                                #{{ $motivo->id_motivo_baja }}
                            </td>

                            <td>
                                {{ $motivo->nombre }}
                            </td>

                            <td>
                                {{ $motivo->descripcion ?? 'Sin descripción' }}
                            </td>

                            <td>

                                <div class="acciones">

                                    <a href="{{ route('motivos_baja.edit', $motivo->id_motivo_baja) }}"
                                       class="btn-editar">
                                        Editar
                                    </a>

                                    <form action="{{ route('motivos_baja.destroy', $motivo->id_motivo_baja) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn-eliminar"
                                                onclick="return confirm('¿Eliminar este motivo?')">
                                            Eliminar
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4"
                                class="sin-registros">
                                No hay motivos registrados.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection