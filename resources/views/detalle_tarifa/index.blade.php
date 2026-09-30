@extends('layouts.template')

@section('titulo', 'Tarifas')

@section('contenido')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Tarifas</h2>
            <p class="text-muted">
                Administración de las tarifas de renta de maquinaria.
            </p>
        </div>

        <a href="{{ route('detalle_tarifa.create') }}"
           class="btn btn-dark">
            Nueva Tarifa
        </a>

    </div>

    <div class="card shadow">

        <div class="card-header">
            <h5 class="mb-0">Lista de Tarifas</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tipo de máquina</th>
                            <th>Período</th>
                            <th>Precio</th>
                            <th>Depósito</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($tarifas as $tarifa)

                            <tr>

                                <td>{{ $tarifa->id_tarifa }}</td>

                                <td>{{ $tarifa->tipo_maquina }}</td>

                                <td>{{ $tarifa->periodo }}</td>

                                <td>
                                    ${{ number_format($tarifa->precio, 2) }}
                                </td>

                                <td>
                                    ${{ number_format($tarifa->deposito, 2) }}
                                </td>

                                <td>{{ $tarifa->fecha_inicio }}</td>

                                <td>{{ $tarifa->fecha_fin }}</td>

                                <td class="text-center">

                                    <a href="{{ route('detalle_tarifa.edit', $tarifa->id_tarifa) }}"
                                       class="btn btn-sm btn-outline-dark">
                                        Editar
                                    </a>

                                    <form action="{{ route('detalle_tarifa.destroy', $tarifa->id_tarifa) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('¿Deseas eliminar esta tarifa?')">
                                            Eliminar
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8"
                                    class="text-center py-4">
                                    No hay tarifas registradas.
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