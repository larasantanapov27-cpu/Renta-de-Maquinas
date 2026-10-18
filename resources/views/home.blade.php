@extends('layouts.template')

@section('titulo', 'Inicio')

@section('contenido')
<style>
    .home-panel {
        color: #202522;
    }

    .home-bienvenida {
        padding: 24px;
        background: #f6f1dc;
        border-radius: 16px;
        margin-bottom: 22px;
    }

    .home-bienvenida h2 {
        font-size: 23px;
        font-weight: bold;
        margin: 0 0 8px;
    }

    .home-bienvenida p {
        color: #62695e;
        margin: 0;
        font-size: 14px;
    }

    .home-indicadores {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .home-tarjeta {
        border: 1px solid #e1e5e2;
        border-radius: 14px;
        padding: 20px;
        background: #fff;
    }

    .home-tarjeta.verde {
        background: #edf6ee;
        border-color: #dcebdc;
    }

    .home-tarjeta.oscura {
        background: #202522;
        border-color: #202522;
        color: #fff;
    }

    .home-etiqueta {
        font-size: 12px;
        margin-bottom: 10px;
    }

    .home-valor {
        font-size: 29px;
        font-weight: bold;
        overflow-wrap: anywhere;
    }

    .home-nota {
        font-size: 11px;
        margin-top: 7px;
        opacity: .75;
    }

    .home-seccion {
        border: 1px solid #e1e5e2;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 22px;
    }

    .home-seccion h3 {
        font-size: 17px;
        font-weight: bold;
        margin-bottom: 16px;
    }

    .home-tabla-scroll {
        overflow-x: auto;
    }

    .home-tabla {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .home-tabla th {
        background: #f4f6f4;
        color: #626b65;
        padding: 12px;
        text-align: left;
        white-space: nowrap;
    }

    .home-tabla td {
        padding: 13px 12px;
        border-bottom: 1px solid #edf0ed;
    }

    .home-estado {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        background: #edf0ed;
        color: #465047;
        white-space: nowrap;
    }

    .home-estado.activa {
        background: #dcefdc;
        color: #24562c;
    }

    .home-estado.programada {
        background: #e6effb;
        color: #285b8f;
    }

    .home-estado.cancelada,
    .home-estado.atrasada {
        background: #fce5e5;
        color: #963737;
    }

    .home-alerta {
        border-left: 4px solid #b44a41;
        background: #fff1ef;
        color: #873b34;
        padding: 15px 18px;
        border-radius: 8px;
        margin-bottom: 22px;
        font-size: 13px;
    }

    .home-acciones {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .home-boton {
        display: inline-block;
        padding: 10px 16px;
        border: 1px solid #dce2dc;
        border-radius: 9px;
        color: #202522;
        text-decoration: none;
        font-size: 13px;
    }

    .home-boton:hover {
        background: #edf3ed;
        color: #202522;
    }

    .home-vacio {
        text-align: center;
        color: #737c75;
        padding: 22px !important;
    }

    @media (max-width: 1100px) {
        .home-indicadores {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .home-indicadores {
            grid-template-columns: 1fr;
        }

        .home-bienvenida {
            padding: 18px;
        }
    }
</style>

<div class="home-panel">
    <div class="home-bienvenida">
        <h2>Bienvenido, {{ auth()->user()->name }}</h2>
        <p>
            Este es el resumen de tu negocio al
            {{ $ahora->format('d/m/Y') }}.
        </p>
    </div>

    <div class="home-indicadores">
        <div class="home-tarjeta verde">
            <div class="home-etiqueta">Máquinas disponibles</div>
            <div class="home-valor">{{ $disponibles }}</div>
            <div class="home-nota">Listas para una nueva renta</div>
        </div>

        <div class="home-tarjeta">
            <div class="home-etiqueta">Máquinas rentadas</div>
            <div class="home-valor">{{ $rentadas }}</div>
            <div class="home-nota">Según su estado registrado</div>
        </div>

        <div class="home-tarjeta">
            <div class="home-etiqueta">Rentas activas</div>
            <div class="home-valor">{{ $rentasActivas }}</div>
            <div class="home-nota">Operaciones en curso</div>
        </div>

        <div class="home-tarjeta oscura">
            <div class="home-etiqueta">Pagos del mes</div>
            <div class="home-valor">
                ${{ number_format($pagosMes, 2) }}
            </div>
            <div class="home-nota">
                MXN · Pagos registrados en {{ $ahora->format('m/Y') }}
            </div>
        </div>

        <div class="home-tarjeta">
            <div class="home-etiqueta">Clientes registrados</div>
            <div class="home-valor">{{ $totalClientes }}</div>
            <div class="home-nota">Total de clientes del negocio</div>
        </div>

        <div class="home-tarjeta">
            <div class="home-etiqueta">Máquinas en mantenimiento</div>
            <div class="home-valor">{{ $mantenimiento }}</div>
            <div class="home-nota">Temporalmente no disponibles</div>
        </div>
    </div>

    @if($rentasAtrasadas > 0)
        <div class="home-alerta" role="status">
            <strong>Devoluciones pendientes:</strong>
            Hay {{ $rentasAtrasadas }}
            {{ $rentasAtrasadas == 1 ? 'renta atrasada' : 'rentas atrasadas' }}
            con maquinaria sin devolver.
        </div>
    @endif

    <div class="home-seccion">
        <h3>Accesos rápidos</h3>

        <div class="home-acciones">
            @if(\Illuminate\Support\Facades\Route::has('rentas.create'))
                @can('rentas.registrar')
<a href="{{ route('rentas.create') }}" class="home-boton">
                    + Nueva renta
                </a>
@endcan
            @endif

            @can('maquinas.consultar')
<a href="{{ route('maquinas.index') }}" class="home-boton">
                Consultar maquinaria
            </a>
@endcan

            @can('clientes.consultar')
<a href="{{ route('clientes.index') }}" class="home-boton">
                Consultar clientes
            </a>
@endcan

            @if(\Illuminate\Support\Facades\Route::has('pagos.create'))
                @can('pagos.registrar')
<a href="{{ route('pagos.create') }}" class="home-boton">
                    + Registrar pago
                </a>
@endcan
            @endif
        </div>
    </div>

    <div class="home-seccion">
        <h3>Devoluciones por atender</h3>
        <p class="home-nota">
            Atrasadas y próximas en los siguientes siete días.
            Se muestran hasta cinco, comenzando por la más antigua.
        </p>

        <div class="home-tabla-scroll">
            <table class="home-tabla">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Devolución programada</th>
                        <th>Situación</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devoluciones as $renta)
                        @php
                            $fecha = \Illuminate\Support\Carbon::parse(
                                $renta->fecha_devolucion_programada
                            );
                            $atrasada = $fecha->lt($ahora);
                        @endphp

                        <tr>
                            <td>#{{ $renta->id_renta }}</td>
                            <td>{{ $renta->cliente }}</td>
                            <td>{{ $fecha->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="home-estado {{ $atrasada ? 'atrasada' : 'programada' }}">
                                    {{ $atrasada ? 'Atrasada' : 'Próxima' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="home-vacio">
                                No hay devoluciones pendientes en este periodo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="home-seccion">
        <h3>Últimas rentas registradas</h3>

        <div class="home-tabla-scroll">
            <table class="home-tabla">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Salida</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimasRentas as $renta)
                        <tr>
                            <td>#{{ $renta->id_renta }}</td>
                            <td>{{ $renta->cliente }}</td>
                            <td>
                                {{ \Illuminate\Support\Carbon::parse(
                                    $renta->fecha_salida
                                )->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <span class="home-estado {{ strtolower($renta->estado) }}">
                                    {{ $renta->estado }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="home-vacio">
                                Todavía no hay rentas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
