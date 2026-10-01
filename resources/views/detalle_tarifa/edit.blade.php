@extends('layouts.template')

@section('titulo', 'Editar Tarifa')

@section('contenido')

<div class="container">

    <div class="card shadow">

        <div class="card-header">
            <h4>Editar Tarifa</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('detalle_tarifa.update', $tarifa->id_tarifa) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Tipo de máquina
                    </label>

                    <select name="id_tipo_maquina"
                            class="form-select"
                            required>

                        @foreach($tiposMaquina as $tipo)

                            <option value="{{ $tipo->id_tipo_maquina }}"
                                {{ old('id_tipo_maquina', $tarifa->id_tipo_maquina) == $tipo->id_tipo_maquina ? 'selected' : '' }}>

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

                        @foreach($periodos as $periodo)

                            <option value="{{ $periodo->id_periodo }}"
                                {{ old('id_periodo', $tarifa->id_periodo) == $periodo->id_periodo ? 'selected' : '' }}>

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
                           value="{{ old('precio', $tarifa->precio) }}"
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
                           value="{{ old('deposito', $tarifa->deposito) }}"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Fecha de inicio
                    </label>

                    <input type="date"
                           name="fecha_inicio"
                           class="form-control"
                           value="{{ old('fecha_inicio', $tarifa->fecha_inicio) }}"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Fecha de fin
                    </label>

                    <input type="date"
                           name="fecha_fin"
                           class="form-control"
                           value="{{ old('fecha_fin', $tarifa->fecha_fin) }}"
                           required>

                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Actualizar
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