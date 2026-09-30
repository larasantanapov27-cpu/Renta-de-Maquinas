@extends('layouts.template')

@section('titulo', 'Tipos de máquina')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="mb-1">Tipos de máquina</h2>

        <p class="text-muted mb-0">
            Administración de los tipos de máquina.
        </p>
    </div>

    <a
        href="{{ route('tipos_maquina.create') }}"
        class="btn btn-dark"
    >
        Nuevo Tipo
    </a>

</div>

<div class="card shadow-sm">

    <div class="card-header bg-white">
        <h5 class="mb-0">
            Tipos de máquina registrados
        </h5>
    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle mb-0">

            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($tipos as $tipo)

                    <tr>

                        <td>
                            {{ $tipo->id_tipo_maquina }}
                        </td>

                        <td>
                            {{ $tipo->nombre }}
                        </td>

                        <td>
                            {{ $tipo->descripcion ?? 'Sin descripción' }}
                        </td>

                        <td class="text-center">

                            <a
                                href="{{ route('tipos_maquina.edit', $tipo->id_tipo_maquina) }}"
                                class="btn btn-outline-dark btn-sm"
                            >
                                Editar
                            </a>

                            <form
                                action="{{ route('tipos_maquina.destroy', $tipo->id_tipo_maquina) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('¿Seguro que deseas eliminar este tipo de máquina?');"
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
                        <td colspan="4" class="text-center py-4">
                            No hay tipos de máquina registrados.
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