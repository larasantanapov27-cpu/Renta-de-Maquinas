@extends('layouts.template')

@section('titulo', 'Registrar Método de Pago')

@section('contenido')

<div class="container">

    <div class="card shadow">

        <div class="card-header">
            <h4>Registrar Método de Pago</h4>
        </div>

        <div class="card-body">

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif

            <form action="{{ route('metodos_pago.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Nombre del método
                    </label>

                    <input type="text"
                           name="nombre"
                           class="form-control"
                           value="{{ old('nombre') }}"
                           placeholder="Ej. Efectivo"
                           required>

                </div>

                <button type="submit"
                        class="btn btn-success">
                    Guardar
                </button>

                <a href="{{ route('metodos_pago.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>

    </div>

</div>

@endsection