@extends('layouts.template')

@section('titulo', 'Nuevo tipo de mantenimiento')

@section('contenido')

<div class="tarjeta-contenido tarjeta-crema"
     style="max-width:700px; margin:auto;">

    <h2 style="font-size:22px; margin-bottom:5px;">
        Nuevo tipo de mantenimiento
    </h2>

    <p style="color:var(--gris); font-size:12px; margin-bottom:25px;">
        Agrega una nueva clasificación de mantenimiento.
    </p>

    <form action="{{ route('tipos_mantenimiento.store') }}"
          method="POST">

        @csrf

        <div class="mb-4">

            <label class="form-label">
                Nombre
            </label>

            <input
                type="text"
                name="nombre"
                value="{{ old('nombre') }}"
                class="form-control"
                maxlength="100"
                required
            >

        </div>

        <div class="mb-4">

            <label class="form-label">
                Descripción
            </label>

            <textarea
                name="descripcion"
                class="form-control"
                maxlength="255"
                rows="4"
            >{{ old('descripcion') }}</textarea>

        </div>

        <div class="d-flex justify-content-end gap-2">

            <a href="{{ route('mantenimientos.index') }}#tipos"
               class="btn btn-light rounded-pill px-4">
                Cancelar
            </a>

            <button
                type="submit"
                class="boton-principal"
            >
                Guardar tipo
            </button>

        </div>

    </form>

</div>

@endsection