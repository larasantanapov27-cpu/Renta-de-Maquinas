@extends('layouts.template')
@section('titulo','Cambiar estado de '.$maquina->codigo)
@section('contenido')
@include('accesos.errores')
<form method="POST" action="{{ route('maquinas.estado',$maquina->id_maquina) }}">@csrf @method('PATCH')
<label>Estado<select name="id_estado_maquina" class="form-select">@foreach($estados as $estado)<option value="{{ $estado->id_estado_maquina }}" @selected($maquina->id_estado_maquina===$estado->id_estado_maquina)>{{ $estado->nombre }}</option>@endforeach</select></label><button class="btn btn-primary">Guardar estado</button></form>
@endsection
