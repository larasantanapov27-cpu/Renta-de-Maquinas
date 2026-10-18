@extends('layouts.template')
@section('titulo',ucfirst(str_replace('_',' ',$modulo)))
@section('contenido')
@can('catalogos.registrar')<a class="btn btn-primary mb-3" href="{{ route($modulo.'.create') }}">Registrar</a>@endcan
<table class="table"><tr><th>Nombre</th><th>Descripción</th><th>Acciones</th></tr>@foreach($registros as $registro)<tr><td>{{ $registro->nombre }}</td><td>{{ $registro->descripcion }}</td><td>@can('catalogos.modificar')<a href="{{ route($modulo.'.edit',$registro->$llave) }}">Editar</a>@endcan</td></tr>@endforeach</table>
@endsection
