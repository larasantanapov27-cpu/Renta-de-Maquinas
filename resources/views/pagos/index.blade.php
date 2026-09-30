@extends('layouts.template')

@section('titulo', 'Pagos')

@section('contenido')

<div class="container">

    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Pagos</h2>

            <p class="text-muted">
                Consulta y administración de los pagos registrados.
            </p>
        </div>

        <a href="{{ route('pagos.create') }}"
           class="btn btn-dark">
            Nuevo Pago
        </a>

    </div>


    {{-- MENSAJE DE ÉXITO --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- MENSAJE DE ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- BUSCADOR DE PAGOS --}}
    <div class="card shadow mb-4">

        <div class="card-header">

            <h5 class="mb-0">
                Buscar Pagos
            </h5>

        </div>

        <div class="card-body">

            <form action="{{ route('pagos.index') }}"
                  method="GET">

                <div class="row g-3">

                    {{-- BUSCAR POR RENTA --}}
                    <div class="col-md-5">

                        <label class="form-label">
                            Número de renta
                        </label>

                        <input type="number"
                               name="renta"
                               class="form-control"
                               value="{{ request('renta') }}"
                               placeholder="Ej. 1"
                               min="1">

                    </div>


                    {{-- BUSCAR POR FECHA --}}
                    <div class="col-md-5">

                        <label class="form-label">
                            Fecha de pago
                        </label>

                        <input type="date"
                               name="fecha"
                               class="form-control"
                               value="{{ request('fecha') }}">

                    </div>


                    {{-- BOTÓN BUSCAR --}}
                    <div class="col-md-2 d-flex align-items-end">

                        <button type="submit"
                                class="btn btn-dark w-100">

                            Buscar

                        </button>

                    </div>

                </div>


                {{-- LIMPIAR BÚSQUEDA --}}
                @if(request('renta') || request('fecha'))

                    <div class="mt-3">

                        <a href="{{ route('pagos.index') }}"
                           class="btn btn-sm btn-secondary">

                            Limpiar búsqueda

                        </a>

                    </div>

                @endif

            </form>

        </div>

    </div>


    {{-- LISTA DE PAGOS --}}
    <div class="card shadow">

        <div class="card-header">

            <h5 class="mb-0">
                Lista de Pagos
            </h5>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    {{-- ENCABEZADOS --}}
                    <thead class="table-dark">

                        <tr>

                            <th>ID</th>

                            <th>Renta</th>

                            <th>Cliente</th>

                            <th>Fecha</th>

                            <th>Método</th>

                            <th>Monto</th>

                            <th>Referencia</th>

                            <th class="text-center">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    {{-- DATOS --}}
                    <tbody>

                        @forelse($pagos as $pago)

                            <tr>

                                {{-- ID PAGO --}}
                                <td>
                                    {{ $pago->id_pago }}
                                </td>


                                {{-- RENTA --}}
                                <td>
                                    #{{ $pago->id_renta }}
                                </td>


                                {{-- CLIENTE --}}
                                <td>

                                    {{ $pago->nombre }}

                                    {{ $pago->apellidos }}

                                </td>


                                {{-- FECHA --}}
                                <td>
                                    {{ $pago->fecha_pago }}
                                </td>


                                {{-- MÉTODO --}}
                                <td>
                                    {{ $pago->metodo_pago }}
                                </td>


                                {{-- MONTO --}}
                                <td>

                                    ${{ number_format($pago->monto, 2) }}

                                </td>


                                {{-- REFERENCIA --}}
                                <td>

                                    {{ $pago->referencia }}

                                </td>


                                {{-- ACCIONES --}}
                                <td class="text-center">


                                    {{-- VER PAGOS DE LA RENTA --}}
                                    <a href="{{ route('pagos.renta', $pago->id_renta) }}"
                                       class="btn btn-sm btn-outline-secondary">

                                        Ver Renta

                                    </a>


                                    {{-- EDITAR PAGO --}}
                                    <a href="{{ route('pagos.edit', $pago->id_pago) }}"
                                       class="btn btn-sm btn-outline-dark">

                                        Editar

                                    </a>


                                    {{-- ELIMINAR PAGO --}}
                                    <form action="{{ route('pagos.destroy', $pago->id_pago) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf

                                        @method('DELETE')


                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('¿Deseas eliminar este pago?')">

                                            Eliminar

                                        </button>

                                    </form>

                                </td>

                            </tr>


                        @empty

                            {{-- SI NO HAY PAGOS --}}
                            <tr>

                                <td colspan="8"
                                    class="text-center py-4">

                                    No se encontraron pagos.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- PIE --}}
        <div class="card-footer">

            <a href="{{ route('panel') }}"
               class="btn btn-secondary">

                Regresar al menú

            </a>

        </div>

    </div>

</div>

@endsection