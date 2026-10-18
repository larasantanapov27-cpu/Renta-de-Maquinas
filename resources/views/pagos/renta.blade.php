@extends('layouts.template')

@section('titulo', 'Pagos de la Renta')

@section('contenido')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2>
                Pagos de la Renta #{{ $renta->id_renta }}
            </h2>

            <p class="text-muted">
                Cliente:
                {{ $renta->nombre }}
                {{ $renta->apellidos }}
            </p>

        </div>

        @can('pagos.consultar')
<a href="{{ route('pagos.index') }}"
           class="btn btn-secondary">

            Regresar a Pagos

        </a>
@endcan

    </div>


    {{-- RESUMEN --}}

    <div class="row mb-4">

        <div class="col-md-4 mb-3">

            <div class="card shadow h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Total de la renta
                    </h6>

                    <h3>
                        ${{ number_format($total, 2) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="card shadow h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Total pagado
                    </h6>

                    <h3>
                        ${{ number_format($pagado, 2) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-4 mb-3">

            <div class="card shadow h-100">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Saldo pendiente
                    </h6>

                    <h3>
                        ${{ number_format($saldo, 2) }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    {{-- HISTORIAL --}}

    <div class="card shadow">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Historial de Pagos
            </h5>

            @can('pagos.registrar')
<a href="{{ route('pagos.create', ['renta' => $renta->id_renta]) }}"
               class="btn btn-dark btn-sm">

                Registrar Pago

            </a>
@endcan

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Método</th>
                            <th>Monto</th>
                            <th>Referencia</th>
                            <th>Observaciones</th>
                            <th class="text-center">
                                Acciones
                            </th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($pagos as $pago)

                            <tr>

                                <td>
                                    {{ $pago->id_pago }}
                                </td>

                                <td>
                                    {{ $pago->fecha_pago }}
                                </td>

                                <td>
                                    {{ $pago->metodo_pago }}
                                </td>

                                <td>
                                    ${{ number_format($pago->monto, 2) }}
                                </td>

                                <td>
                                    {{ $pago->referencia }}
                                </td>

                                <td>
                                    {{ $pago->observaciones }}
                                </td>

                                <td class="text-center">

                                    @can('pagos.modificar')
<a href="{{ route('pagos.edit', $pago->id_pago) }}"
                                       class="btn btn-sm btn-outline-dark">

                                        Editar

                                    </a>
@endcan



                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7"
                                    class="text-center py-4">

                                    Esta renta no tiene pagos registrados.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
