@extends('layouts.template')

@section('titulo', 'Editar Cliente')

@section('contenido')

<div class="container">

    <div class="card shadow">

        <div class="card-header">
            <h4>Editar Cliente</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('clientes.update', $cliente->id_cliente) }}"
                  method="POST">

                @csrf
                @method('PUT')

                @include('accesos.persona')

                <div class="mb-3">

                    <label class="form-label">
                        Correo
                    </label>

                    <input type="email"
                           name="correo"
                           class="form-control"
                           value="{{ $cliente->correo }}"
                           required>

                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Actualizar
                </button>

                @can('clientes.consultar')
<a href="{{ route('clientes.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>
@endcan

            </form>

        </div>

    </div>

</div>

@endsection
