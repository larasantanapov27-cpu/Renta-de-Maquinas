@extends('layouts.template')

@section('titulo', 'Registrar Período')

@section('contenido')

<div class="container">

    <div class="card shadow">

        <div class="card-header">
            <h4>Registrar Período de Renta</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('periodos_renta.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Nombre del período
                    </label>

                    <input type="text"
                           name="nombre"
                           class="form-control"
                           value="{{ old('nombre') }}"
                           placeholder="Ej. Día"
                           required>

                </div>

                <button type="submit"
                        class="btn btn-success">
                    Guardar
                </button>

                <a href="{{ route('periodos_renta.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>

    </div>

</div>

@endsection