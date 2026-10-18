@extends('layouts.template')
@section('titulo','Administración de usuarios')
@section('contenido')
@include('accesos.errores')
<div class="table-responsive"><table class="table"><thead><tr><th>Cuenta</th><th>Estado / rol</th><th>Operaciones</th></tr></thead><tbody>
@foreach($usuarios as $usuario)<tr><td>{{ $usuario->name }}<br>{{ $usuario->email }}</td><td>{{ $usuario->estado }}<br>{{ $usuario->rol?->nombre ?? 'Sin rol' }}</td><td>
<form method="POST" action="{{ route('accesos.usuario',$usuario->id) }}" onsubmit="return confirm('¿Confirmas el cambio de acceso?')">@csrf @method('PATCH')
<select name="id_rol" class="form-select mb-2"><option value="">Selecciona un rol activo</option>@foreach($roles as $rol)<option value="{{ $rol->id_rol }}" @selected($usuario->id_rol===$rol->id_rol)>{{ $rol->nombre }}</option>@endforeach</select>
@if($usuario->estado==='pendiente')
@can('usuarios.validar')@can('usuarios.asignar_rol')<button class="btn btn-success" name="operacion" value="aprobar">Aprobar</button>@endcan<button class="btn btn-danger" name="operacion" value="rechazar">Rechazar</button>@endcan
@endif
@can('usuarios.asignar_rol')<button class="btn btn-primary" name="operacion" value="rol">Asignar rol</button>@endcan
@can('usuarios.cambiar_estado')
@if($usuario->estado==='inactivo')<button class="btn btn-success" name="operacion" value="activar">Activar</button>@endif
@if($usuario->estado==='activo')<button class="btn btn-warning" name="operacion" value="desactivar">Desactivar</button>@endif
@endcan</form></td></tr>@endforeach
</tbody></table></div>{{ $usuarios->links() }}
@endsection
