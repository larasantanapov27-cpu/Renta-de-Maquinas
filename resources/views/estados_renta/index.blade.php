@extends('layouts.template')

@section('titulo', 'Estados de renta')

@section('contenido')
    <div class="tarjeta-contenido">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="h4 mb-1">Estados de renta</h2>

                <p class="text-muted mb-0">
                    Administra el catálogo de estados del sistema.
                </p>
            </div>

            <a href="{{ route('estados_renta.create') }}"
               class="boton-principal">
                + Nuevo estado
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Descripción</th>
                        <th scope="col" class="text-center">Rentas asociadas</th>
                        <th scope="col" class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($estados as $estado)
                        <tr>
                            <td>{{ $estado->id_estado_renta }}</td>

                            <td class="fw-semibold">
                                {{ $estado->nombre }}
                            </td>

                            <td style="min-width: 180px; overflow-wrap: anywhere;">
                                {{ $estado->descripcion ?: 'Sin descripción' }}
                            </td>

                            <td class="text-center">
                                <span class="badge rounded-pill bg-secondary">
                                    {{ $estado->rentas_count }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    <a
                                        href="{{ route('estados_renta.edit', $estado) }}"
                                        class="btn btn-outline-dark btn-sm"
                                        aria-label="Editar estado {{ $estado->nombre }}"
                                    >
                                        Editar
                                    </a>

                                    @if ($estado->rentas_count > 0)
                                        <span
                                            title="Este estado tiene rentas asociadas"
                                        >
                                            <button
                                                type="button"
                                                class="btn btn-outline-secondary btn-sm"
                                                disabled
                                            >
                                                En uso
                                            </button>
                                        </span>
                                    @else
                                        <form
                                            action="{{ route('estados_renta.destroy', $estado) }}"
                                            method="POST"
                                            onsubmit="return confirm('¿Deseas eliminar este estado de renta? Esta acción no se puede deshacer.');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-outline-danger btn-sm"
                                                aria-label="Eliminar estado {{ $estado->nombre }}"
                                            >
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                No hay estados registrados. Presiona
                                <strong>Nuevo estado</strong> para agregar uno.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($estados->hasPages())
            <div class="mt-4">
                {{ $estados->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
@endsection