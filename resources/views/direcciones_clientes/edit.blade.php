@extends('layouts.template')

@section('titulo', 'Editar Dirección')

@section('contenido')

<div class="container">

    <div class="card shadow">

        <div class="card-header">
            <h4>Editar Dirección del Cliente</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('direcciones_clientes.update', $direccion->id_direccion) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Cliente
                    </label>

                    <select name="id_cliente"
                            class="form-select"
                            required>

                        @foreach($clientes as $cliente)

                            <option value="{{ $cliente->id_cliente }}"
                                {{ $direccion->id_cliente == $cliente->id_cliente ? 'selected' : '' }}>

                                {{ $cliente->persona->nombre ?? '' }}
                                {{ $cliente->persona->apellidos ?? '' }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="mb-3">
                    <label class="form-label">Calle</label>

                    <input type="text"
                           name="calle"
                           class="form-control"
                           value="{{ $direccion->calle }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Número Exterior</label>

                    <input type="text"
                           name="numero_exterior"
                           class="form-control"
                           value="{{ $direccion->numero_exterior }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Número Interior</label>

                    <input type="text"
                           name="numero_interior"
                           class="form-control"
                           value="{{ $direccion->numero_interior }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Colonia</label>

                    <input type="text"
                           name="colonia"
                           class="form-control"
                           value="{{ $direccion->colonia }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Municipio</label>

                    <input type="text"
                           name="municipio"
                           class="form-control"
                           value="{{ $direccion->municipio }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado</label>

                    <input type="text"
                           name="estado"
                           class="form-control"
                           value="{{ $direccion->estado }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Código Postal</label>

                    <input type="text"
                           name="codigo_postal"
                           class="form-control"
                           value="{{ $direccion->codigo_postal }}"
                           required>
                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Referencias
                    </label>

                    <textarea name="referencias"
                              class="form-control"
                              rows="3">{{ $direccion->referencias }}</textarea>

                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Actualizar
                </button>

                <a href="{{ route('direcciones_clientes.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>

    </div>

</div>

@endsection