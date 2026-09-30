@extends('layouts.template')

@section('titulo', 'Proveedores')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1">Lista de proveedores</h4>
        <p class="text-muted mb-0">
            Consulta y administra los proveedores registrados.
        </p>
    </div>

    <a href="{{ route('proveedores.create') }}"
       class="btn btn-dark">
        Nuevo proveedor
    </a>

</div>


<div class="card shadow-sm border-0">

    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">
            Proveedores registrados
        </h5>
    </div>

    <div class="card-body">

        @if($proveedores->count() > 0)

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Teléfono</th>
                            <th>RFC</th>
                            <th>Correo</th>
                            <th>Dirección</th>
                            <th class="text-center">Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($proveedores as $proveedor)

                            <tr>

                                <td>
                                    {{ $proveedor->id_proveedor }}
                                </td>

                                <td>
                                    {{ $proveedor->persona->nombre ?? 'Sin nombre' }}
                                </td>

                                <td>
                                    {{ $proveedor->persona->apellidos ?? 'Sin apellidos' }}
                                </td>

                                <td>
                                    {{ $proveedor->persona->telefono ?? 'Sin teléfono' }}
                                </td>

                                <td>
                                    {{ $proveedor->rfc }}
                                </td>

                                <td>
                                    {{ $proveedor->correo }}
                                </td>

                                <td>
                                    {{ $proveedor->direccion }}
                                </td>

                                <td class="text-center">

                                    <a href="{{ route('proveedores.edit', $proveedor->id_proveedor) }}"
                                       class="btn btn-primary btn-sm">
                                        Editar
                                    </a>


                                    <form action="{{ route('proveedores.destroy', $proveedor->id_proveedor) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Está seguro de eliminar este proveedor?')">
                                            Eliminar
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="alert alert-info mb-0">
                No hay proveedores registrados.
            </div>

        @endif

    </div>

</div>

@endsection