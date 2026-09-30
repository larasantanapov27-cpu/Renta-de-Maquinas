@extends('layouts.template')

@section('titulo', 'Registrar Persona')

@section('contenido')

<div class="container">

    <div class="card shadow">

        <div class="card-header">
            <h4>Registrar Persona</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('personas.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Nombre
                    </label>

                    <input type="text"
                           name="nombre"
                           class="form-control"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Apellidos
                    </label>

                    <input type="text"
                           name="apellidos"
                           class="form-control"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Teléfono
                    </label>

                    <input type="text"
                           name="telefono"
                           class="form-control"
                           required>

                </div>

                <button type="submit"
                        class="btn btn-success">
                    Guardar
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