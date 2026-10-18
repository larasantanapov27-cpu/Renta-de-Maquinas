@extends('layouts.template')
@section('titulo', 'Panel de '.$rol->nombre)
@section('contenido')
<p>{{ $rol->descripcion }}</p>
<div class="row g-3">
@foreach($indicadores as $nombre=>$total)<div class="col-md-4"><div class="card card-body"><span>{{ $nombre }}</span><strong class="fs-3">{{ $total }}</strong></div></div>@endforeach
</div>
<p class="mt-4">Selecciona una operación en el menú. Las opciones corresponden a tus permisos actuales.</p>
@endsection
