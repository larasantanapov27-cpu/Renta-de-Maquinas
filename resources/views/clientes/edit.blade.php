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

                <div class="mb-3">

                    <label class="form-label">
                        Persona
                    </label>

                    <select name="id_persona"
                            class="form-select"
                            required>

                        @foreach($personas as $persona)

                            <option value="{{ $persona->id_persona }}"
                                {{ $cliente->id_persona == $persona->id_persona ? 'selected' : '' }}>

                                {{ $persona->nombre }}
                                {{ $persona->apellidos }}

                            </option>

                        @endforeach

                    </select>

                </div>

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

                <a href="{{ route('clientes.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>

    </div>

</div>

@endsection