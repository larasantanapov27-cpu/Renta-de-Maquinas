@extends('layouts.template')

@section('titulo', 'Métodos de Pago')

@section('contenido')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Métodos de Pago</h2>
            <p class="text-muted">
                Administración de los métodos disponibles para registrar pagos.
            </p>
        </div>

        <a href="{{ route('metodos_pago.create') }}"
           class="btn btn-dark">
            Nuevo Método
        </a>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow">

        <div class="card-header">
            <h5 class="mb-0">Lista de Métodos de Pago</h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Método de pago</th>
                            <th class="text-center">Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($metodos as $metodo)

                            <tr>

                                <td>
                                    {{ $metodo->id_metodo_pago }}
                                </td>

                                <td>
                                    {{ $metodo->nombre }}
                                </td>

                                <td class="text-center">

                                    <a href="{{ route('metodos_pago.edit', $metodo->id_metodo_pago) }}"
                                       class="btn btn-sm btn-outline-dark">
                                        Editar
                                    </a>

                                    <form action="{{ route('metodos_pago.destroy', $metodo->id_metodo_pago) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('¿Deseas eliminar este método de pago?')">
                                            Eliminar
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="3"
                                    class="text-center py-4">
                                    No hay métodos de pago registrados.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer">

            <a href="{{ route('pagos.index') }}"
               class="btn btn-secondary">
                Regresar a Pagos
            </a>

        </div>

    </div>

</div>

@endsection