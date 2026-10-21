@extends('layouts.template')

@section('titulo', 'Períodos de Renta')

@section('contenido')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Períodos de Renta</h2>
            <p class="text-muted">
                Administración de los períodos utilizados en las tarifas.
            </p>
        </div>

        @can('catalogos.registrar')
<a href="{{ route('periodos_renta.create') }}"
           class="btn btn-dark">
            Nuevo Período
        </a>
@endcan

    </div>

    <div class="card shadow">

        <div class="card-header">
            <h5 class="mb-0">Lista de Períodos</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Período</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($periodos as $periodo)

                            <tr>

                                <td>{{ $periodo->id_periodo }}</td>

                                <td>{{ $periodo->nombre }}</td>

                                <td class="text-center">

                                    @include('crud.acciones', ['modulo'=>'periodos_renta', 'id'=>$periodo->id_periodo])
@can('catalogos.modificar')
<a href="{{ route('periodos_renta.edit', $periodo->id_periodo) }}"
                                       class="btn btn-sm btn-outline-dark">
                                        Editar
                                    </a>
@endcan



                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3"
                                    class="text-center py-4">
                                    No hay períodos registrados.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer">

            @can('tarifas.consultar')
<a href="{{ route('detalle_tarifa.index') }}"
               class="btn btn-secondary">
                Regresar a Tarifas
            </a>
@endcan

        </div>

    </div>

</div>

@endsection
