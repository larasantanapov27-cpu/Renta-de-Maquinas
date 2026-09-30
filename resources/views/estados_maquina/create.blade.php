@extends('layouts.template')

@section('titulo', 'Nuevo Estado de Máquina')

@section('contenido')

<div class="mb-4">

    <h2 class="mb-1">
        Nuevo Estado de Máquina
    </h2>

    <p class="text-muted mb-0">
        Registra un nuevo estado para las máquinas.
    </p>

</div>

<div class="card shadow-sm">

    <div class="card-header bg-dark text-white">
        Datos del Estado
    </div>

    <div class="card-body">

        <form
            action="{{ route('estados_maquina.store') }}"
            method="POST"
        >

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    class="form-control"
                    value="{{ old('nombre') }}"
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
                >{{ old('descripcion') }}</textarea>

            </div>

            <button
                type="submit"
                class="btn btn-dark"
            >
                Guardar
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