@extends('layouts.template')

@section('titulo', 'Rentas')

@section('contenido')
    <div class="tarjeta-contenido">

        {{-- Encabezado --}}
        <div class="mb-4">
            <h2 class="h4 mb-1">Rentas registradas</h2>

            <p class="text-muted mb-0">
                Consulta las rentas, los pagos recibidos y los saldos.
            </p>
        </div>

        {{-- Búsqueda --}}
        <form
            action="{{ route('rentas.index') }}"
            method="GET"
            class="row g-3 align-items-end mb-4"
        >
            <div class="col-12 col-md-8">
                <label for="buscar" class="form-label">
                    Buscar por folio, nombre o apellido
                </label>

                <input
                    type="search"
                    id="buscar"
                    name="buscar"
                    class="form-control"
                    value="{{ $buscar }}"
                    maxlength="100"
                    placeholder="Escribe un folio, nombre o apellido"
                >
            </div>

            <div class="col-12 col-md-4">
                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="boton-principal">
                        Buscar
                    </button>

                    <a
                        href="{{ route('rentas.index') }}"
                        class="btn btn-outline-secondary rounded-pill"
                    >
                        Limpiar
                    </a>
                </div>
            </div>
        </form>

        <p class="text-muted small">
            Resultados: {{ $rentas->total() }}
        </p>

        {{-- Listado --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th scope="col">Folio</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Salida</th>
                        <th scope="col">Devolución programada</th>
                        <th scope="col">Estado de renta</th>
                        <th scope="col" class="text-end">Total a cubrir</th>
                        <th scope="col" class="text-end">Pagado</th>
                        <th scope="col" class="text-end">Saldo</th>
                        <th scope="col">Estado de pago</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($rentas as $renta)
                        @php
                            $nombreCliente = trim(
                                ($renta->cliente_nombre ?? '') . ' ' .
                                ($renta->cliente_apellidos ?? '')
                            );

                            $calculable = !in_array(
                                $renta->estado_pago,
                                ['Sin calcular', 'Revisar importes'],
                                true
                            );

                            $clasePago = match ($renta->estado_pago) {
                                'Liquidado' => 'bg-success',
                                'Pendiente' => 'bg-warning text-dark',
                                'Revisar importes' => 'bg-danger',
                                default => 'bg-secondary',
                            };
                        @endphp

                        <tr>
                            <td class="fw-semibold text-nowrap">
                                #{{ $renta->id_renta }}
                            </td>

                            <td style="min-width: 180px;">
                                {{ $nombreCliente ?: 'Sin cliente asociado' }}
                            </td>

                            <td class="text-nowrap">
                                {{ $renta->fecha_salida?->format('d/m/Y H:i') ?? '—' }}
                            </td>

                            <td class="text-nowrap">
                                {{ $renta->fecha_devolucion_programada?->format('d/m/Y H:i') ?? '—' }}
                            </td>

                            <td>
                                <span class="badge rounded-pill bg-secondary">
                                    {{ $renta->estado?->nombre ?? 'Sin estado' }}
                                </span>
                            </td>

                            <td class="text-end text-nowrap">
                                @if ($renta->cantidad_detalles > 0)
                                    ${{ number_format($renta->total_cubrir, 2) }}
                                @else
                                    —
                                @endif
                            </td>

                            <td class="text-end text-nowrap">
                                ${{ number_format($renta->total_pagado, 2) }}
                            </td>

                            <td class="text-end text-nowrap">
                                @if (!$calculable)
                                    —
                                @elseif ($renta->saldo < 0)
                                    <span class="text-success">
                                        ${{ number_format(abs($renta->saldo), 2) }}
                                    </span>

                                    <small class="d-block text-muted">
                                        A favor
                                    </small>
                                @else
                                    <span class="{{ $renta->estado_pago === 'Pendiente' ? 'text-danger fw-semibold' : 'text-success' }}">
                                        ${{ number_format($renta->saldo, 2) }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="badge rounded-pill {{ $clasePago }}">
                                    {{ $renta->estado_pago }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="9"
                                class="text-center text-muted py-5"
                            >
                                @if ($buscar !== '')
                                    No se encontraron rentas con esa búsqueda.
                                @else
                                    Todavía no hay rentas registradas.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <p class="text-muted small mt-3 mb-0">
            Importes en MXN. El total a cubrir incluye el depósito
            de garantía. El estado de pago corresponde a los cargos
            y pagos registrados hasta el momento.
        </p>

        {{-- Paginación --}}
        @if ($rentas->hasPages())
            <div class="mt-4">
                {{ $rentas->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
@endsection