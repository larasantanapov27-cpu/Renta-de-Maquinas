@extends('layouts.template')
@section('titulo','Matriz de permisos')
@section('contenido')
@include('accesos.errores')
<div class="alert alert-warning">Los cambios afectan inmediatamente a todos los usuarios del rol. Las eliminaciones están deshabilitadas. La seguridad se reserva al SuperAdmin.</div>
@foreach($roles as $rol)
<form method="POST" action="{{ route('accesos.permisos.guardar',$rol->id_rol) }}" class="card card-body mb-4" onsubmit="return confirm('¿Guardar los permisos para todos los usuarios de este rol?')">@csrf @method('PUT')
<h2 class="h4">{{ $rol->nombre }}</h2>
@foreach($grupos as $modulo=>$permisos)
<fieldset class="mb-3"><legend class="h6">{{ ucfirst($modulo) }}</legend>
@foreach($permisos as $permiso)
@php($bloqueado=$permiso->accion==='eliminar' || ($rol->clave!=='superadmin' && in_array($modulo,['usuarios','roles','permisos'])))
<label class="d-block"><input type="checkbox" name="permisos[]" value="{{ $permiso->id_permiso }}" @checked(!$bloqueado && $rol->permisos->contains(fn($p)=>$p->id_permiso===$permiso->id_permiso && $p->pivot->activo)) @disabled($bloqueado || !auth()->user()->can('permisos.configurar'))> {{ $permiso->clave }} — {{ $permiso->descripcion }}</label>
@endforeach</fieldset>@endforeach
@can('permisos.configurar')<button class="btn btn-primary">Guardar permisos de {{ $rol->nombre }}</button>@endcan
</form>@endforeach
@endsection
