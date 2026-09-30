@extends('layouts.template')

@section('titulo', 'Máquinas')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="mb-1">Máquinas</h2>
        <p class="text-muted mb-0">
            Administración de las máquinas registradas.
        </p>
    </div>

    <a href="{{ route('maquinas.create') }}" class="btn btn-dark">
        Nueva Máquina
    </a>

</div>

<div class="card shadow-sm">

    <div class="card-header bg-white">
        <h5 class="mb-0">Lista de Máquinas</h5>
    </div>

    <div class="table-responsive">

        <table class="table table-bordered table-hover align-middle mb-0">

            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Número de serie</th>
                    <th>Fecha ingreso</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($maquinas as $maquina)

                    <tr>

                        <td>
                            {{ $maquina->id_maquina }}
                        </td>

                        <td>
                            {{ $maquina->codigo }}
                        </td>

                        <td>
                            {{ $maquina->tipoMaquina->nombre ?? 'Sin tipo' }}
                        </td>

                        <td>
                            {{ $maquina->estadoMaquina->nombre ?? 'Sin estado' }}
                        </td>

                        <td>
                            {{ $maquina->marca }}
                        </td>

                        <td>
                            {{ $maquina->modelo }}
                        </td>

                        <td>
                            {{ $maquina->numero_serie }}
                        </td>

                        <td>
                            {{ $maquina->fecha_ingreso }}
                        </td>

                        <td class="text-center">

                            <a
                                href="{{ route('maquinas.edit', $maquina->id_maquina) }}"
                                class="btn btn-outline-dark btn-sm"
                            >
                                Editar
                            </a>

                            <form
                                action="{{ route('maquinas.destroy', $maquina->id_maquina) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('¿Seguro que deseas eliminar esta máquina?');"
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
                        <td colspan="9" class="text-center py-4">
                            No hay máquinas registradas.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="card-footer bg-white">

        <a href="{{ route('panel') }}" class="btn btn-secondary">
            Regresar al menú
        </a>

    </div>

</div>

@endsection