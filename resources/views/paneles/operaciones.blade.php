@extends('layouts.template')
@section('titulo', 'Operaciones autorizadas')
@section('contenido')
<p>Introduce el identificador del registro para abrir la operación autorizada.</p>
@foreach($operations as $operation)
<form method="GET" action="{{ route('operaciones.abrir') }}" class="mb-3">
<input type="hidden" name="modulo" value="{{ $modulo }}"><input type="hidden" name="operacion" value="{{ $operation }}">
<label>Identificador <input class="form-control" name="id" type="number" min="1" required></label><button class="btn btn-primary">{{ $operation }}</button>
</form>
@endforeach
@endsection
