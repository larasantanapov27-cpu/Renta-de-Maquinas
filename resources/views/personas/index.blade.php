@extends('layouts.template')

@section('titulo', 'Personas')

@section('contenido')

<style>
    .pagina-modulo {
        padding: 30px 35px;
    }

    .encabezado-pagina {
        background-color: #ffffff;
        border: 1px solid #dfe5eb;
        border-left: 5px solid #34495e;
        border-radius: 10px;
        padding: 24px 28px;
        margin-bottom: 25px;
        box-shadow: 0 3px 12px rgba(31, 41, 55, 0.05);
    }

    .titulo-pagina {
        color: #263746;
        font-size: 27px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .descripcion-pagina {
        color: #7a858f;
        font-size: 14px;
        margin-bottom: 0;
    }

    .btn-nuevo {
        background-color: #34495e;
        border: 1px solid #34495e;
        color: white;
        font-weight: 600;
    }

    .btn-nuevo:hover {
        background-color: #263746;
        color: white;
    }

    .card-modulo {
        background-color: white;
        border: 1px solid #dfe5eb;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(31, 41, 55, .06);
    }

    .card-encabezado {
        padding: 19px 25px;
        border-bottom: 1px solid #e5e9ed;
    }

    .tabla-modulo {
        margin-bottom: 0;
    }

    .tabla-modulo thead th {
        background-color: #34495e;
        color: white;
        padding: 15px 17px;
        border: none;
    }

    .tabla-modulo tbody td {
        padding: 14px 17px;
        vertical-align: middle;
    }

    .id-registro {
        background-color: #edf2f6;
        padding: 4px 8px;
        border-radius: 5px;
        font-weight: 700;
    }

    .card-pie {
        background-color: #fafbfc;
        border-top: 1px solid #e5e9ed;
        padding: 17px 25px;
    }
</style>

<div class="container-fluid pagina-modulo">

    <div class="encabezado-pagina">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h2 class="titulo-pagina">Personas</h2>

                <p class="descripcion-pagina">
                    Administración de las personas registradas en el sistema.
                </p>
            </div>

            <a href="{{ route('personas.create') }}"
               class="btn btn-nuevo">
                Nueva Persona
            </a>

        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card-modulo">

        <div class="card-encabezado">
            <h5 class="mb-0">Lista de Personas</h5>
        </div>

        <div class="table-responsive">

            <table class="table tabla-modulo align-middle">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($personas as $persona)

                        <tr>

                            <td>
                                <span class="id-registro">
                                    {{ $persona->id_persona }}
                                </span>
                            </td>

                            <td>{{ $persona->nombre }}</td>

                            <td>{{ $persona->apellidos }}</td>

                            <td>{{ $persona->telefono }}</td>

                            <td class="text-center">

                                <a href="{{ route('personas.edit', $persona->id_persona) }}"
                                   class="btn btn-sm btn-outline-dark">
                                    Editar
                                </a>

                                <form action="{{ route('personas.destroy', $persona->id_persona) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('¿Deseas eliminar esta persona?')">
                                        Eliminar
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center py-4">
                                No hay personas registradas.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="card-pie">
            <a href="{{ route('panel') }}"
               class="btn btn-secondary">
                Regresar al menú
            </a>
        </div>

    </div>

</div>

@endsection