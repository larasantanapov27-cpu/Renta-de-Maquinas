@extends('layouts.template')
@section('titulo', 'Detalle de '.str_replace('_',' ',$modulo))
@section('contenido')
<div class="card mb-3"><div class="card-body">
<dl class="row mb-0">
@foreach((array)$registro as $campo=>$valor)
<dt class="col-sm-4">{{ ucfirst(str_replace('_',' ',$campo)) }}</dt><dd class="col-sm-8 text-break">{{ $valor ?? 'Sin registrar' }}</dd>
@endforeach
</dl></div></div>
@foreach($relacionados as $tabla=>$filas)
<h2 class="h5">{{ ucfirst(str_replace('_',' ',$tabla)) }}</h2>
<div class="table-responsive mb-3"><table class="table table-bordered table-hover">
@if($filas->isNotEmpty())
<thead class="table-dark"><tr>@foreach((array)$filas->first() as $campo=>$valor)<th>{{ ucfirst(str_replace('_',' ',$campo)) }}</th>@endforeach</tr></thead>
<tbody>@foreach($filas as $fila)<tr>@foreach((array)$fila as $valor)<td>{{ $valor ?? 'Sin registrar' }}</td>@endforeach</tr>@endforeach</tbody>
@else <tr><td>Sin registros relacionados.</td></tr> @endif
</table></div>
@endforeach
@include('crud.acciones', ['mostrarVer'=>false, 'id'=>$registro->{\App\Services\RegistroCrud::LLAVES[$modulo]}])
@php($permiso = isset(\App\Services\Acceso::CATALOGOS[$modulo]) ? 'catalogos' : \App\Services\Acceso::modulo($modulo))
@can($permiso.'.modificar')<a class="btn btn-dark" href="{{ route($modulo.'.edit',$registro->{\App\Services\RegistroCrud::LLAVES[$modulo]}) }}">Editar</a>@endcan
<a class="btn btn-secondary" href="{{ route($modulo.'.index') }}">Volver</a>
@if(!\App\Services\RegistroCrud::eliminable($modulo))<p class="text-muted mt-3">Este registro se conserva como historial. Para retirar maquinaria registra su baja.</p>@endif
@endsection
