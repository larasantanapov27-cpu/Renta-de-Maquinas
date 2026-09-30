@extends('layouts.template')

@section('titulo', 'Editar proveedor')

@section('contenido')

<div class="row justify-content-center">

    <div class="col-lg-8">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">
                    Editar proveedor
                </h5>

            </div>


            <div class="card-body">

                <form action="{{ route('proveedores.update', $proveedor->id_proveedor) }}"
                      method="POST">

                    @csrf
                    @method('PUT')


                    {{-- PERSONA --}}

                    <div class="mb-3">

                        <label for="id_persona"
                               class="form-label">
                            Persona
                        </label>

                        <select name="id_persona"
                                id="id_persona"
                                class="form-select"
                                required>

                            <option value="">
                                Seleccione una persona
                            </option>

                            @foreach($personas as $persona)

                                <option value="{{ $persona->id_persona }}"
                                    {{ old('id_persona', $proveedor->id_persona) == $persona->id_persona ? 'selected' : '' }}>

                                    {{ $persona->nombre }}
                                    {{ $persona->apellidos }}

                                </option>

                            @endforeach

                        </select>

                    </div>


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
                               value="{{ old('rfc', $proveedor->rfc) }}"
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
                               value="{{ old('correo', $proveedor->correo) }}">

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
                                  rows="3">{{ old('direccion', $proveedor->direccion) }}</textarea>

                    </div>


                    {{-- BOTONES --}}

                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary">
                            Actualizar
                        </button>

                        <a href="{{ route('proveedores.index') }}"
                           class="btn btn-secondary">
                            Cancelar
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection