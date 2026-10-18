@extends('layouts.template')
@section('titulo','Roles')
@section('contenido')
@include('accesos.errores')
@foreach($roles as $rol)<div class="card card-body mb-3"><h2 class="h5">{{ $rol->nombre }}</h2><p>{{ $rol->descripcion }} · {{ $rol->activo?'Activo':'Inactivo' }}</p>
@can('roles.cambiar_estado')<form method="POST" action="{{ route('accesos.rol',$rol->id_rol) }}" onsubmit="return confirm('Este cambio afecta a todos los usuarios del rol. ¿Continuar?')">@csrf @method('PATCH')<input type="hidden" name="activo" value="{{ $rol->activo?0:1 }}"><button class="btn btn-primary">{{ $rol->activo?'Desactivar':'Activar' }}</button></form>@endcan</div>@endforeach
@endsection
