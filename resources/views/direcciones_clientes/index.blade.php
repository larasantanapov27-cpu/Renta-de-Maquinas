@extends('layouts.template')

@section('titulo', 'Direcciones de Clientes')

@section('contenido')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Direcciones de Clientes</h2>
            <p class="text-muted">
                Administración de las direcciones registradas.
            </p>
        </div>

        <a href="{{ route('direcciones_clientes.create') }}"
           class="btn btn-dark">
            Nueva Dirección
        </a>

    </div>

    <div class="card shadow">

        <div class="card-header">
            <h5 class="mb-0">Lista de Direcciones</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Calle</th>
                            <th>No. Exterior</th>
                            <th>Colonia</th>
                            <th>Municipio</th>
                            <th>Estado</th>
                            <th>C.P.</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($direcciones as $direccion)

                            <tr>

                                <td>
                                    {{ $direccion->id_direccion }}
                                </td>

                                <td>
                                    {{ $direccion->cliente->persona->nombre ?? 'Sin cliente' }}
                                    {{ $direccion->cliente->persona->apellidos ?? '' }}
                                </td>

                                <td>
                                    {{ $direccion->calle }}
                                </td>

                                <td>
                                    {{ $direccion->numero_exterior }}
                                </td>

                                <td>
                                    {{ $direccion->colonia }}
                                </td>

                                <td>
                                    {{ $direccion->municipio }}
                                </td>

                                <td>
                                    {{ $direccion->estado }}
                                </td>

                                <td>
                                    {{ $direccion->codigo_postal }}
                                </td>

                                <td>

                                    <div class="d-flex gap-2">

                                        <a href="{{ route('direcciones_clientes.edit', $direccion->id_direccion) }}"
                                           class="btn btn-sm btn-outline-dark">
                                            Editar
                                        </a>

                                        <form action="{{ route('direcciones_clientes.destroy', $direccion->id_direccion) }}"
                                              method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('¿Deseas eliminar esta dirección?')">
                                                Eliminar
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9"
                                    class="text-center py-4">
                                    No hay direcciones registradas.
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