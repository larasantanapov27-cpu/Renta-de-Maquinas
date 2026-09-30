@extends('layouts.template')

@section('titulo', 'Editar mantenimiento')

@section('contenido')

<style>
    .formulario-san-juan {
        max-width: 850px;
        margin: 0 auto;
        padding: 28px;
        border-radius: 20px;
        border: 1px solid #e9ede9;
        background: #e2f0fc;
    }

    .formulario-san-juan h2 {
        margin: 0 0 5px;
        font-size: 22px;
        font-weight: bold;
    }

    .descripcion-form {
        margin-bottom: 25px;
        color: var(--gris);
        font-size: 12px;
    }

    .fila-formulario {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .campo {
        margin-bottom: 18px;
    }

    .campo label {
        display: block;
        margin-bottom: 7px;
        font-size: 12px;
        font-weight: bold;
    }

    .campo input,
    .campo select,
    .campo textarea {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #dfe5df;
        border-radius: 12px;
        background: white;
    }

    .campo textarea {
        min-height: 90px;
    }

    .acciones-formulario {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
    }

    .boton-cancelar,
    .boton-guardar {
        padding: 10px 20px;
        border: 0;
        border-radius: 25px;
        text-decoration: none;
        font-size: 13px;
    }

    .boton-cancelar {
        background: white;
        color: var(--oscuro);
    }

    .boton-guardar {
        background: var(--oscuro);
        color: white;
    }

    @media(max-width: 650px) {
        .fila-formulario {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="formulario-san-juan">

    <h2>Editar mantenimiento</h2>

    <p class="descripcion-form">
        Modifica la información del mantenimiento.
    </p>

    <form
        action="{{ route(
            'mantenimientos.update',
            $mantenimiento->id_mantenimiento
        ) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div class="fila-formulario">

            <div class="campo">

                <label>Máquina</label>

                <select name="id_maquina" required>

                    @foreach($maquinas as $maquina)

                        <option
                            value="{{ $maquina->id_maquina }}"
                            @selected(
                                old(
                                    'id_maquina',
                                    $mantenimiento->id_maquina
                                ) == $maquina->id_maquina
                            )
                        >
                            {{ $maquina->codigo }}
                            - {{ $maquina->marca }}
                            {{ $maquina->modelo }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="campo">

                <label>Tipo de mantenimiento</label>

                <select name="id_tipo_mantenimiento" required>

                    @foreach($tipos as $tipo)

                        <option
                            value="{{ $tipo->id_tipo_mantenimiento }}"
                            @selected(
                                old(
                                    'id_tipo_mantenimiento',
                                    $mantenimiento->id_tipo_mantenimiento
                                ) == $tipo->id_tipo_mantenimiento
                            )
                        >
                            {{ $tipo->nombre }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>


        <div class="fila-formulario">

            <div class="campo">

                <label>Fecha de entrada</label>

                <input
                    type="datetime-local"
                    name="fecha_entrada"
                    value="{{ old(
                        'fecha_entrada',
                        $mantenimiento->fecha_entrada
                            ? date(
                                'Y-m-d\TH:i',
                                strtotime($mantenimiento->fecha_entrada)
                            )
                            : ''
                    ) }}"
                    required
                >

            </div>

            <div class="campo">

                <label>Fecha de salida</label>

                <input
                    type="datetime-local"
                    name="fecha_salida"
                    value="{{ old(
                        'fecha_salida',
                        $mantenimiento->fecha_salida
                            ? date(
                                'Y-m-d\TH:i',
                                strtotime($mantenimiento->fecha_salida)
                            )
                            : ''
                    ) }}"
                >

            </div>

        </div>


        <div class="campo">

            <label>Costo</label>

            <input
                type="number"
                name="costo"
                step="0.01"
                min="0"
                value="{{ old(
                    'costo',
                    $mantenimiento->costo
                ) }}"
            >

        </div>


        <div class="campo">

            <label>Descripción</label>

            <textarea name="descripcion">{{ old(
                'descripcion',
                $mantenimiento->descripcion
            ) }}</textarea>

        </div>


        <div class="campo">

            <label>Observaciones</label>

            <textarea name="observaciones">{{ old(
                'observaciones',
                $mantenimiento->observaciones
            ) }}</textarea>

        </div>


        <div class="acciones-formulario">

            <a href="{{ route('mantenimientos.index') }}"
               class="boton-cancelar">
                Cancelar
            </a>

            <button type="submit"
                    class="boton-guardar">
                Guardar cambios
            </button>

        </div>

    </form>

</div>

@endsection