@extends('layouts.template')
@section('titulo',isset($renta)?'Editar renta':'Registrar renta')
@section('contenido')
@include('accesos.errores')
<form action="{{ isset($renta)?route('rentas.update',$renta->id_renta):route('rentas.store') }}" method="POST">@csrf
@isset($renta) @method('PUT') @else
<label class="d-block mb-3">Cliente<select class="form-select" name="id_cliente" required>@foreach($clientes as $cliente)<option value="{{ $cliente->id_cliente }}">{{ $cliente->nombre }} {{ $cliente->apellidos }}</option>@endforeach</select></label>
<label class="d-block mb-3">Salida<input class="form-control" type="datetime-local" name="fecha_salida" value="{{ old('fecha_salida') }}" required></label>
@endisset
<label class="d-block mb-3">Devolución programada<input class="form-control" type="datetime-local" name="fecha_devolucion_programada" value="{{ old('fecha_devolucion_programada',isset($renta)?$renta->fecha_devolucion_programada->format('Y-m-d\TH:i'):'') }}" required></label>
<label class="d-block mb-3">Observaciones<textarea class="form-control" name="observaciones">{{ old('observaciones',$renta->observaciones ?? '') }}</textarea></label>
@unless(isset($renta))
<div id="detalles"><fieldset class="card card-body mb-3 detalle"><legend class="h6">Equipo</legend>
<label>Máquina<select class="form-select" name="detalles[0][id_maquina]">@foreach($maquinas as $maquina)<option value="{{ $maquina->id_maquina }}">{{ $maquina->codigo }}</option>@endforeach</select></label>
<label>Tarifa vigente<select class="form-select" name="detalles[0][id_tarifa]">@foreach($tarifas as $tarifa)<option value="{{ $tarifa->id_tarifa }}">Tipo {{ $tarifa->id_tipo_maquina }} · {{ $tarifa->periodo }} · ${{ $tarifa->precio }}</option>@endforeach</select></label>
<label>Periodos<input class="form-control" type="number" step="0.01" min="0.01" name="detalles[0][cantidad_periodos]" value="1" required></label>
<button type="button" class="btn btn-outline-danger mt-2" onclick="if(document.querySelectorAll('.detalle').length>1)this.closest('fieldset').remove()">Quitar equipo</button>
</fieldset></div>
<button type="button" class="btn btn-secondary mb-3" id="agregar">Agregar equipo</button>
<p>Los precios y depósitos corresponden a la tarifa vigente.</p>
@endunless
<button class="btn btn-success">Guardar renta</button></form>
@endsection
@push('scripts')
<script>let siguiente=1;document.getElementById('agregar')?.addEventListener('click',()=>{const copia=document.querySelector('.detalle').cloneNode(true);copia.querySelectorAll('[name]').forEach(e=>e.name=e.name.replace(/\[\d+\]/,'['+siguiente+']'));siguiente++;document.getElementById('detalles').appendChild(copia)});</script>
@endpush
