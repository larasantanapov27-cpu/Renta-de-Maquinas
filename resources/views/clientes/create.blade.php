@extends('layouts.template')

@section('titulo', 'Registrar Cliente')

@section('contenido')

<div class="container">

    <div class="card shadow">

        <div class="card-header">
            <h4>Registrar Cliente</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('clientes.store') }}"
                  method="POST">

                @csrf

                @include('accesos.persona')

                <div class="mb-3">

                    <label class="form-label">
                        Correo
                    </label>

                    <input type="email"
                           name="correo"
                           class="form-control"
                           required>

                </div>

                <button type="submit"
                        class="btn btn-success">
                    Guardar
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
