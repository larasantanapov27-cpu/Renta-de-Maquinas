@extends('layouts.template')

@section('contenido')
<style>
    .formulario-san-juan {
        max-width: 850px;
        margin: 0 auto;
        padding: 28px;
        border: 1px solid #e9ede9;
        border-radius: 20px;
        background: #f7f0da;
    }

    .formulario-san-juan h2 {
        margin: 0 0 5px;
        font-size: 22px;
        font-weight: bold;
    }

    .formulario-san-juan .descripcion-form {
        margin-bottom: 25px;
        color: var(--gris);
        font-size: 12px;
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
        color: var(--texto);
    }

    .campo textarea {
        min-height: 90px;
        resize: vertical;
    }

    .fila-formulario {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
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

    <h2>Nuevo mantenimiento</h2>

    <p class="descripcion-form">
        Registra el mantenimiento realizado a una máquina.
    </p>

    <form action="{{ route('mantenimientos.store') }}"
          method="POST">

        @csrf

        <div class="fila-formulario">

            <div class="campo">

                <label>Máquina</label>

                <select name="id_maquina" required>

                    <option value="">
                        Selecciona una máquina
                    </option>

                    @foreach($maquinas as $maquina)

                        <option
                            value="{{ $maquina->id_maquina }}"
                            @selected(old('id_maquina') == $maquina->id_maquina)
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

                    <option value="">
                        Selecciona un tipo
                    </option>

                    @foreach($tipos as $tipo)

                        <option
                            value="{{ $tipo->id_tipo_mantenimiento }}"
                            @selected(
                                old('id_tipo_mantenimiento')
                                == $tipo->id_tipo_mantenimiento
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
                    value="{{ old('fecha_entrada') }}"
                    required
                >
            </div>

            <div class="campo">
                <label>Fecha de salida</label>

                <input
                    type="datetime-local"
                    name="fecha_salida"
                    value="{{ old('fecha_salida') }}"
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
                value="{{ old('costo') }}"
                placeholder="0.00"
            >

        </div>


        <div class="campo">

            <label>Descripción</label>

            <textarea
                name="descripcion"
                placeholder="Descripción del mantenimiento"
            >{{ old('descripcion') }}</textarea>

        </div>


        <div class="campo">

            <label>Observaciones</label>

            <textarea
                name="observaciones"
                placeholder="Observaciones adicionales"
            >{{ old('observaciones') }}</textarea>

        </div>


        <div class="acciones-formulario">

            <a href="{{ route('mantenimientos.index') }}"
               class="boton-cancelar">
                Cancelar
            </a>

            <button type="submit"
                    class="boton-guardar">
                Guardar mantenimiento
            </button>

        </div>

    </form>

</div>

@endsection