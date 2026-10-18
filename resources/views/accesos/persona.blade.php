@foreach(['nombre'=>'Nombre','apellidos'=>'Apellidos','telefono'=>'Teléfono'] as $campo=>$etiqueta)
<div class="mb-3"><label class="form-label">{{ $etiqueta }}</label><input class="form-control" name="persona[{{ $campo }}]" value="{{ old('persona.'.$campo, $cliente->persona->$campo ?? $proveedor->persona->$campo ?? '') }}" maxlength="{{ $campo==='telefono'?20:($campo==='apellidos'?200:100) }}" required></div>
@endforeach
