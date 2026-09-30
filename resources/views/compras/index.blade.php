@extends('layouts.template')

@section('titulo', 'Compras')

@section('contenido')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="mb-1">Compras</h2>

        <p class="text-muted mb-0">
            Administración de las compras registradas.
        </p>
    </div>

    <a href="{{ route('compras.create') }}" class="btn btn-dark">
        Nueva Compra
    </a>

</div>


<div class="card shadow-sm">

    <div class="card-header bg-dark text-white">
        Compras registradas
    </div>

    <div class="table-responsive">

        <table
            class="table table-bordered table-hover align-middle mb-0"
            style="min-width: 1200px;"
        >

            <thead class="table-dark">

                <tr>

                    <th style="width: 60px;">
                        ID
                    </th>

                    <th style="min-width: 170px;">
                        Proveedor
                    </th>

                    <th style="min-width: 190px;">
                        Máquina
                    </th>

                    <th style="min-width: 110px;">
                        Fecha
                    </th>

                    <th style="min-width: 110px;">
                        Precio
                    </th>

                    <th style="min-width: 130px;">
                        Factura
                    </th>

                    <th style="min-width: 300px;">
                        Observaciones
                    </th>

                    <th
                        class="text-center"
                        style="min-width: 160px; width: 160px;"
                    >
                        Acciones
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($compras as $compra)

                    <tr>

                        <td>
                            {{ $compra->id_compra }}
                        </td>


                        <td>

                            @if($compra->proveedor && $compra->proveedor->persona)

                                {{ $compra->proveedor->persona->nombre }}
                                {{ $compra->proveedor->persona->apellidos }}

                            @else

                                Sin proveedor

                            @endif

                        </td>


                        <td>

                            @if($compra->maquina)

                                {{ $compra->maquina->codigo }}
                                -
                                {{ $compra->maquina->marca }}
                                {{ $compra->maquina->modelo }}

                            @else

                                Sin máquina

                            @endif

                        </td>


                        <td>
                            {{ $compra->fecha_compra }}
                        </td>


                        <td style="white-space: nowrap;">

                            ${{ number_format($compra->precio_compra, 2) }}

                        </td>


                        <td style="white-space: nowrap;">

                            {{ $compra->numero_factura }}

                        </td>


                        <td>

                            {{ $compra->observaciones ?? 'Sin observaciones' }}

                        </td>


                        <td
                            class="text-center"
                            style="white-space: nowrap; min-width: 160px;"
                        >

                            <div
                                class="d-flex justify-content-center align-items-center gap-1 flex-nowrap"
                            >

                                <a
                                    href="{{ route('compras.edit', $compra->id_compra) }}"
                                    class="btn btn-outline-dark btn-sm"
                                >
                                    Editar
                                </a>


                                <form
                                    action="{{ route('compras.destroy', $compra->id_compra) }}"
                                    method="POST"
                                    class="m-0 p-0"
                                    onsubmit="return confirm('¿Seguro que deseas eliminar esta compra?');"
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

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-4"
                        >
                            No hay compras registradas.
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