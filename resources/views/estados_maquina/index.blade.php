@extends('layouts.template')

@section('titulo', 'Estados de máquina')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1">
            Estados de máquina
        </h2>

        <p class="text-muted mb-0">
            Administración de los estados de las máquinas.
        </p>

    </div>

    <a
        href="{{ route('estados_maquina.create') }}"
        class="btn btn-dark"
    >
        Nuevo Estado
    </a>

</div>

<div class="card shadow-sm">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            Estados de máquina registrados
        </h5>

    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle mb-0">

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th class="text-center">
                        Acciones
                    </th>
                </tr>

            </thead>

            <tbody>

                @forelse($estados as $estado)

                    <tr>

                        <td>
                            {{ $estado->id_estado_maquina }}
                        </td>

                        <td>
                            {{ $estado->nombre }}
                        </td>

                        <td>
                            {{ $estado->descripcion ?? 'Sin descripción' }}
                        </td>

                        <td class="text-center">

                            <a
                                href="{{ route('estados_maquina.edit', $estado->id_estado_maquina) }}"
                                class="btn btn-outline-dark btn-sm"
                            >
                                Editar
                            </a>

                            <form
                                action="{{ route('estados_maquina.destroy', $estado->id_estado_maquina) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('¿Seguro que deseas eliminar este estado de máquina?');"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-outline-danger btn-sm"
                                >
                                    Eliminar
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="4"
                            class="text-center py-4"
                        >
                            No hay estados de máquina registrados.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer bg-white">

        <a
            href="{{ route('panel') }}"
            class="btn btn-secondary"
        >
            Regresar al menú
        </a>

    </div>

</div>

@endsection