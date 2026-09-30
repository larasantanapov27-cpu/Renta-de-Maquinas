@extends('layouts.template')

@section('titulo', 'Editar Máquina')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Editar Máquina</h2>
        <p class="text-muted mb-0">
            Modifica los datos de la máquina seleccionada.
        </p>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Corrige los siguientes errores:</strong>

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow-sm">

    <div class="card-header bg-dark text-white">
        Datos de la máquina
    </div>

    <div class="card-body">

        <form action="{{ route('maquinas.update', $maquina->id_maquina) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="row">

                {{-- CÓDIGO --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Código
                    </label>

                    <input
                        type="text"
                        name="codigo"
                        class="form-control"
                        value="{{ old('codigo', $maquina->codigo) }}"
                        required
                    >
                </div>


                {{-- TIPO DE MÁQUINA --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Tipo de máquina
                    </label>

                    <select
                        name="id_tipo_maquina"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Selecciona un tipo
                        </option>

                        @foreach($tiposMaquina as $tipo)
                            <option
                                value="{{ $tipo->id_tipo_maquina }}"
                                {{ old('id_tipo_maquina', $maquina->id_tipo_maquina) == $tipo->id_tipo_maquina ? 'selected' : '' }}
                            >
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach

                    </select>
                </div>


                {{-- ESTADO DE MÁQUINA --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Estado de máquina
                    </label>

                    <select
                        name="id_estado_maquina"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Selecciona un estado
                        </option>

                        @foreach($estadosMaquina as $estado)
                            <option
                                value="{{ $estado->id_estado_maquina }}"
                                {{ old('id_estado_maquina', $maquina->id_estado_maquina) == $estado->id_estado_maquina ? 'selected' : '' }}
                            >
                                {{ $estado->nombre }}
                            </option>
                        @endforeach

                    </select>
                </div>


                {{-- MARCA --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Marca
                    </label>

                    <input
                        type="text"
                        name="marca"
                        class="form-control"
                        value="{{ old('marca', $maquina->marca) }}"
                        required
                    >
                </div>


                {{-- MODELO --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Modelo
                    </label>

                    <input
                        type="text"
                        name="modelo"
                        class="form-control"
                        value="{{ old('modelo', $maquina->modelo) }}"
                    >
                </div>


                {{-- NÚMERO DE SERIE --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Número de serie
                    </label>

                    <input
                        type="text"
                        name="numero_serie"
                        class="form-control"
                        value="{{ old('numero_serie', $maquina->numero_serie) }}"
                    >
                </div>


                {{-- FECHA DE INGRESO --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Fecha de ingreso
                    </label>

                    <input
                        type="date"
                        name="fecha_ingreso"
                        class="form-control"
                        value="{{ old('fecha_ingreso', $maquina->fecha_ingreso) }}"
                        required
                    >
                </div>


                {{-- DESCRIPCIÓN --}}
                <div class="col-12 mb-3">
                    <label class="form-label">
                        Descripción
                    </label>

                    <textarea
                        name="descripcion"
                        class="form-control"
                        rows="3"
                    >{{ old('descripcion', $maquina->descripcion) }}</textarea>
                </div>


                {{-- OBSERVACIONES --}}
                <div class="col-12 mb-3">
                    <label class="form-label">
                        Observaciones
                    </label>

                    <textarea
                        name="observaciones"
                        class="form-control"
                        rows="3"
                    >{{ old('observaciones', $maquina->observaciones) }}</textarea>
                </div>

            </div>


            <div class="d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-dark"
                >
                    Actualizar
                </button>

                <a
                    href="{{ route('maquinas.index') }}"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>

@endsection