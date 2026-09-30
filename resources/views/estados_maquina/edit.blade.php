@extends('layouts.template')

@section('titulo', 'Editar Estado de Máquina')

@section('contenido')

<div class="mb-4">

    <h2 class="mb-1">
        Editar Estado de Máquina
    </h2>

    <p class="text-muted mb-0">
        Modifica la información del estado de máquina.
    </p>

</div>

<div class="card shadow-sm">

    <div class="card-header bg-dark text-white">
        Datos del Estado
    </div>

    <div class="card-body">

        <form
            action="{{ route('estados_maquina.update', $estado->id_estado_maquina) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label class="form-label">
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    class="form-control"
                    value="{{ old('nombre', $estado->nombre) }}"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Descripción
                </label>

                <textarea
                    name="descripcion"
                    class="form-control"
                    rows="4"
                >{{ old('descripcion', $estado->descripcion) }}</textarea>

            </div>

            <button
                type="submit"
                class="btn btn-dark"
            >
                Actualizar
            </button>

            <a
                href="{{ route('estados_maquina.index') }}"
                class="btn btn-secondary"
            >
                Cancelar
            </a>

        </form>

    </div>

</div>

@endsection