@extends('layouts.template')

@section('titulo', 'Clientes')

@section('contenido')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Clientes</h2>
            <p class="text-muted">
                Administración de los clientes registrados.
            </p>
        </div>

        <a href="{{ route('clientes.create') }}"
           class="btn btn-dark">
            Nuevo Cliente
        </a>

    </div>

    <div class="card shadow">

        <div class="card-header">
            <h5 class="mb-0">Lista de Clientes</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th class="text-center">Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($clientes as $cliente)

                            <tr>

                                <td>{{ $cliente->id_cliente }}</td>

                                <td>
                                    {{ $cliente->persona->nombre ?? 'Sin persona' }}
                                </td>

                                <td>
                                    {{ $cliente->persona->apellidos ?? '' }}
                                </td>

                                <td>
                                    {{ $cliente->persona->telefono ?? '' }}
                                </td>

                                <td>
                                    {{ $cliente->correo }}
                                </td>

                                <td class="text-center">

                                    <a href="{{ route('clientes.edit', $cliente->id_cliente) }}"
                                       class="btn btn-sm btn-outline-dark">
                                        Editar
                                    </a>

                                    <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('¿Deseas eliminar este cliente?')">
                                            Eliminar
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6"
                                    class="text-center py-4">
                                    No hay clientes registrados.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer">

            <a href="{{ route('panel') }}"
               class="btn btn-secondary">
                Regresar al menú
            </a>

        </div>

    </div>

</div>

@endsection