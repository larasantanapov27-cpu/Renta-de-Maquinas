@extends('layouts.template')

@section('titulo', 'Nuevo estado de renta')

@section('contenido')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <div class="tarjeta-contenido">
                <h2 class="h4 mb-2">Registrar estado</h2>

                <p class="text-muted mb-4">
                    Captura el nombre y, si lo deseas, una descripción.
                </p>

                <form
                    action="{{ route('estados_renta.store') }}"
                    method="POST"
                >
                    @csrf

                    <div class="mb-4">
                        <label for="nombre" class="form-label fw-semibold">
                            Nombre del estado *
                        </label>

                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            class="form-control @error('nombre') is-invalid @enderror"
                            value="{{ old('nombre') }}"
                            maxlength="50"
                            placeholder="Ejemplo: En curso"
                            required
                            autofocus
                            @error('nombre')
                                aria-invalid="true"
                                aria-describedby="error-nombre"
                            @enderror
                        >

                        @error('nombre')
                            <div id="error-nombre" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="descripcion" class="form-label fw-semibold">
                            Descripción
                        </label>

                        <textarea
                            id="descripcion"
                            name="descripcion"
                            class="form-control @error('descripcion') is-invalid @enderror"
                            rows="4"
                            maxlength="255"
                            placeholder="Describe cuándo se utiliza este estado."
                            @error('descripcion')
                                aria-invalid="true"
                                aria-describedby="error-descripcion"
                            @enderror
                        >{{ old('descripcion') }}</textarea>

                        @error('descripcion')
                            <div id="error-descripcion" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            Opcional. Máximo 255 caracteres.
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2">
                        <a
                            href="{{ route('estados_renta.index') }}"
                            class="btn btn-outline-secondary rounded-pill px-4"
                        >
                            Cancelar
                        </a>

                        <button type="submit" class="boton-principal">
                            Guardar estado
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection