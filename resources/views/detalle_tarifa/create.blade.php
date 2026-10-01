@extends('layouts.template')

@section('titulo', 'Registrar Tarifa')

@section('contenido')

<div class="container">

    <div class="card shadow">

        <div class="card-header">
            <h4>Registrar Tarifa</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('detalle_tarifa.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Tipo de máquina
                    </label>

                    <select name="id_tipo_maquina"
                            class="form-select"
                            required>

                        <option value="">
                            Selecciona un tipo de máquina
                        </option>

                        @foreach($tiposMaquina as $tipo)

                            <option value="{{ $tipo->id_tipo_maquina }}"
                                {{ old('id_tipo_maquina') == $tipo->id_tipo_maquina ? 'selected' : '' }}>
                                {{ $tipo->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Período de renta
                    </label>

                    <select name="id_periodo"
                            class="form-select"
                            required>

                        <option value="">
                            Selecciona un período
                        </option>

                        @foreach($periodos as $periodo)

                            <option value="{{ $periodo->id_periodo }}"
                                {{ old('id_periodo') == $periodo->id_periodo ? 'selected' : '' }}>
                                {{ $periodo->nombre }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Precio
                    </label>

                    <input type="number"
                           name="precio"
                           class="form-control"
                           step="0.01"
                           min="0"
                           value="{{ old('precio') }}"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Depósito
                    </label>

                    <input type="number"
                           name="deposito"
                           class="form-control"
                           step="0.01"
                           min="0"
                           value="{{ old('deposito') }}"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Fecha de inicio
                    </label>

                    <input type="date"
                           name="fecha_inicio"
                           class="form-control"
                           value="{{ old('fecha_inicio') }}"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Fecha de fin
                    </label>

                    <input type="date"
                           name="fecha_fin"
                           class="form-control"
                           value="{{ old('fecha_fin') }}"
                           required>

                </div>

                <button type="submit"
                        class="btn btn-success">
                    Guardar
                </button>

                <a href="{{ route('detalle_tarifa.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>

    </div>

</div>

@endsection