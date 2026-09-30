@extends('layouts.template')

@section('titulo', 'Editar Persona')

@section('contenido')

<div class="container">

    <div class="card shadow">

        <div class="card-header">
            <h4>Editar Persona</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('personas.update', $persona->id_persona) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Nombre
                    </label>

                    <input type="text"
                           name="nombre"
                           class="form-control"
                           value="{{ $persona->nombre }}"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Apellidos
                    </label>

                    <input type="text"
                           name="apellidos"
                           class="form-control"
                           value="{{ $persona->apellidos }}"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Teléfono
                    </label>

                    <input type="text"
                           name="telefono"
                           class="form-control"
                           value="{{ $persona->telefono }}"
                           required>

                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Actualizar
                </button>

                <a href="{{ route('personas.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>

    </div>

</div>

@endsection