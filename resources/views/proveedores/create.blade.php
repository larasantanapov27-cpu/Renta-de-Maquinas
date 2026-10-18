@extends('layouts.template')

@section('titulo', 'Nuevo proveedor')

@section('contenido')

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">
                    Registrar proveedor
                </h5>

            </div>


            <div class="card-body">

                <form action="{{ route('proveedores.store') }}"
                      method="POST">

                    @csrf


                    {{-- PERSONA --}}

                    @include('accesos.persona')


                    {{-- RFC --}}

                    <div class="mb-3">

                        <label for="rfc"
                               class="form-label">
                            RFC
                        </label>

                        <input type="text"
                               name="rfc"
                               id="rfc"
                               class="form-control"
                               value="{{ old('rfc') }}"
                               placeholder="Ingrese el RFC"
                               required>

                    </div>


                    {{-- CORREO --}}

                    <div class="mb-3">

                        <label for="correo"
                               class="form-label">
                            Correo electrónico
                        </label>

                        <input type="email"
                               name="correo"
                               id="correo"
                               class="form-control"
                               value="{{ old('correo') }}"
                               placeholder="ejemplo@correo.com">

                    </div>


                    {{-- DIRECCIÓN --}}

                    <div class="mb-3">

                        <label for="direccion"
                               class="form-label">
                            Dirección
                        </label>

                        <textarea name="direccion"
                                  id="direccion"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Ingrese la dirección">{{ old('direccion') }}</textarea>

                    </div>


                    {{-- BOTONES --}}

                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-success">
                            Guardar
                        </button>

                        @can('proveedores.consultar')
<a href="{{ route('proveedores.index') }}"
                           class="btn btn-secondary">
                            Cancelar
                        </a>
@endcan

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
