@extends('layouts.template') 

@section('contenido')

<style>
    .form-container {
        padding: 40px;
        display: flex;
        justify-content: center;
    }

    .form-card {
        width: 100%;
        max-width: 700px;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0,0,0,.10);
    }

    .form-header {
        background: #222522;
        padding: 28px 32px;
        color: white;
    }

    .form-header span {
        color: #aeb4ae;
        font-size: 13px;
        letter-spacing: 2px;
    }

    .form-header h2 {
        margin: 8px 0 0;
    }

    .form-body {
        padding: 32px;
    }

    .campo {
        margin-bottom: 22px;
    }

    .campo label {
        display: block;
        margin-bottom: 8px;
        color: #444944;
    }

    .campo input,
    .campo textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #d3d7d3;
        border-radius: 7px;
        box-sizing: border-box;
    }

    .campo textarea {
        min-height: 110px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .btn-cancelar {
        padding: 11px 20px;
        background: #e4e7e4;
        color: #222522;
        text-decoration: none;
        border-radius: 7px;
    }

    .btn-guardar {
        padding: 11px 20px;
        background: #222522;
        color: white;
        border: none;
        border-radius: 7px;
        cursor: pointer;
    }
</style>


<div class="form-container">

    <div class="form-card">

        <div class="form-header">

            <span>CATÁLOGO · MOTIVOS DE BAJA</span>

            <h2>Nuevo motivo</h2>

        </div>


        <div class="form-body">

            <form action="{{ route('motivos_baja.store') }}"
                  method="POST">

                @csrf


                <div class="campo">

                    <label>Nombre</label>

                    <input type="text"
                           name="nombre"
                           maxlength="100"
                           required>

                </div>


                <div class="campo">

                    <label>Descripción</label>

                    <textarea name="descripcion"
                              maxlength="255"></textarea>

                </div>


                <div class="form-actions">

                    <a href="{{ route('bajas_maquinas.index') }}"
                       class="btn-cancelar">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="btn-guardar">
                        Guardar motivo
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection