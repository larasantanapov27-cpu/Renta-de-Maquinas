@extends('layouts.template')

@section('titulo', 'Registrar Pago')

@section('contenido')

<div class="container">

    <div class="card shadow">

        <div class="card-header">
            <h4>Registrar Pago</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('pagos.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Renta
                    </label>

                    <select name="id_renta"
                            class="form-select"
                            required>

                        <option value="">
                            Selecciona una renta
                        </option>

                        @foreach($rentas as $renta)

                            <option value="{{ $renta->id_renta }}"
                                {{ old('id_renta') == $renta->id_renta ? 'selected' : '' }}>

                                Renta #{{ $renta->id_renta }}
                                -
                                {{ $renta->nombre }}
                                {{ $renta->apellidos }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Método de pago
                    </label>

                    <select name="id_metodo_pago"
                            class="form-select"
                            required>

                        <option value="">
                            Selecciona un método
                        </option>

                        @foreach($metodos as $metodo)

                            <option value="{{ $metodo->id_metodo_pago }}"
                                {{ old('id_metodo_pago') == $metodo->id_metodo_pago ? 'selected' : '' }}>

                                {{ $metodo->nombre }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Fecha de pago
                    </label>

                    <input type="datetime-local"
                           name="fecha_pago"
                           class="form-control"
                           value="{{ old('fecha_pago') }}"
                           required>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Monto
                    </label>

                    <input type="number"
                           name="monto"
                           class="form-control"
                           step="0.01"
                           min="0.01"
                           value="{{ old('monto') }}"
                           required>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Referencia
                    </label>

                    <input type="text"
                           name="referencia"
                           class="form-control"
                           value="{{ old('referencia') }}"
                           placeholder="Ej. PAGO-EF-011"
                           required>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Observaciones
                    </label>

                    <textarea name="observaciones"
                              class="form-control"
                              rows="3"
                              required>{{ old('observaciones') }}</textarea>

                </div>


                <button type="submit"
                        class="btn btn-success">
                    Guardar
                </button>

                <a href="{{ route('pagos.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>

    </div>

</div>

@endsection