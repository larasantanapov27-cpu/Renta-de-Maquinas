@php
$permiso = isset(\App\Services\Acceso::CATALOGOS[$modulo]) ? 'catalogos' : \App\Services\Acceso::modulo($modulo);
@endphp
@if($mostrarVer ?? true)
@can($permiso.'.consultar')
<a class="btn btn-sm btn-outline-dark" href="{{ route($modulo.'.show',$id) }}">Ver</a>
@endcan
@endif
@if(\App\Services\RegistroCrud::eliminable($modulo))
@can($permiso.'.eliminar')
<form class="d-inline" method="POST" action="{{ route($modulo.'.destroy',$id) }}" onsubmit="return confirm('¿Borrar este registro? Esta acción no se puede deshacer.');">
@csrf @method('DELETE')
<button class="btn btn-sm btn-outline-danger" type="submit">Borrar</button>
</form>
@endcan
@endif
