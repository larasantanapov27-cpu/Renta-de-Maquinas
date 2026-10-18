@extends('layouts.template')
@section('titulo','Devolución de renta #'.$renta->id_renta)
@section('contenido')
@include('accesos.errores')
<form method="POST" action="{{ route('rentas.devolver',$renta->id_renta) }}">@csrf @method('PATCH')
@foreach($renta->detalles->whereNull('fecha_devolucion_real') as $detalle)<label class="d-block"><input type="checkbox" name="detalles[]" value="{{ $detalle->id_detalle_renta }}"> Máquina #{{ $detalle->id_maquina }} · Detalle #{{ $detalle->id_detalle_renta }}</label>@endforeach
<label class="d-block my-3">Fecha real de devolución<input class="form-control" name="fecha_devolucion_real" type="datetime-local" value="{{ now()->format('Y-m-d\TH:i') }}" required></label>
<button class="btn btn-success">Registrar devolución</button></form>
@endsection
