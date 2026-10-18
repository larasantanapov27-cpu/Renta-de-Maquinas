@extends('layouts.template')
@section('titulo','Detalles de renta #'.$renta->id_renta)
@section('contenido')
<p>Estado: {{ $renta->estado->nombre }}. Devolución programada: {{ $renta->fecha_devolucion_programada }}</p>
<table class="table"><tr><th>Máquina</th><th>Periodos</th><th>Precio</th><th>Devolución real</th></tr>@foreach($renta->detalles as $detalle)<tr><td>{{ $detalle->id_maquina }}</td><td>{{ $detalle->cantidad_periodos }}</td><td>{{ $detalle->precio_aplicado }}</td><td>{{ $detalle->fecha_devolucion_real ?? 'Pendiente' }}</td></tr>@endforeach</table>
@endsection
