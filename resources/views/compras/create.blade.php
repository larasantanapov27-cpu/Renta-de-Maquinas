@extends('layouts.template')

@section('titulo', 'Nueva Compra')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Nueva Compra</h2>
        <p class="text-muted mb-0">
            Registra una nueva compra de maquinaria.
        </p>
    </div>
</div>

<div class="card shadow-sm">

    <div class="card-header bg-dark text-white">
        Datos de la compra
    </div>

    <div class="card-body">

        <form action="{{ route('compras.store') }}" method="POST">

            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Proveedor
                    </label>

                    <select
                        name="id_proveedor"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Selecciona un proveedor
                        </option>

                        @foreach($proveedores as $proveedor)

                            <option
                                value="{{ $proveedor->id_proveedor }}"
                                {{ old('id_proveedor') == $proveedor->id_proveedor ? 'selected' : '' }}
                            >

                                @if($proveedor->persona)

                                    {{ $proveedor->persona->nombre }}
                                    {{ $proveedor->persona->apellidos }}

                                @else

                                    Proveedor {{ $proveedor->id_proveedor }}

                                @endif

                            </option>

                        @endforeach

                    </select>
                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Máquina
                    </label>

                    <select
                        name="id_maquina"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Selecciona una máquina
                        </option>

                        @foreach($maquinas as $maquina)

                            <option
                                value="{{ $maquina->id_maquina }}"
                                {{ old('id_maquina') == $maquina->id_maquina ? 'selected' : '' }}
                            >
                                {{ $maquina->codigo }}
                                -
                                {{ $maquina->marca }}
                                {{ $maquina->modelo }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Fecha de compra
                    </label>

                    <input
                        type="date"
                        name="fecha_compra"
                        class="form-control"
                        value="{{ old('fecha_compra') }}"
                        required
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Precio de compra
                    </label>

                    <input
                        type="number"
                        name="precio_compra"
                        class="form-control"
                        step="0.01"
                        min="0"
                        value="{{ old('precio_compra') }}"
                        required
                    >

                </div>


                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Número de factura
                    </label>

                    <input
                        type="text"
                        name="numero_factura"
                        class="form-control"
                        value="{{ old('numero_factura') }}"
                    >

                </div>


                <div class="col-12 mb-3">

                    <label class="form-label">
                        Observaciones
                    </label>

                    <textarea
                        name="observaciones"
                        class="form-control"
                        rows="4"
                    >{{ old('observaciones') }}</textarea>

                </div>

            </div>


            <div class="d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-dark"
                >
                    Guardar
                </button>

                <a
                    href="{{ route('compras.index') }}"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>

@endsection