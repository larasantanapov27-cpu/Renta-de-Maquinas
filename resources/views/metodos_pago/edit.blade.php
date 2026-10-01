@extends('layouts.template')

@section('titulo', 'Editar Método de Pago')

@section('contenido')

<div class="container">

    <div class="card shadow">

        <div class="card-header">
            <h4>Editar Método de Pago</h4>
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

            <form action="{{ route('metodos_pago.update', $metodo->id_metodo_pago) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Nombre del método
                    </label>

                    <input type="text"
                           name="nombre"
                           class="form-control"
                           value="{{ old('nombre', $metodo->nombre) }}"
                           required>

                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Actualizar
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