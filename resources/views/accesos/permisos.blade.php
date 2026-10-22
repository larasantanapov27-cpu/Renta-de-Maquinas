@extends('layouts.template')

@section('titulo', 'Matriz de permisos')

@section('contenido')
<<<<<<< HEAD
<style>
    .permisos-page {
        --pm-primary: #4f46e5;
        --pm-border: #e5eaf2;
        --pm-text: #24324b;
        --pm-muted: #64748b;

        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 32px 20px;
        color: var(--pm-text);
    }

    .permisos-page .pm-heading {
        margin-bottom: 26px;
        text-align: center;
    }

    .permisos-page .pm-heading h1 {
        margin-bottom: 8px;
        color: #1e293b;
        font-size: clamp(1.6rem, 3vw, 2rem);
        font-weight: 750;
        letter-spacing: -.04em;
    }

    .permisos-page .pm-heading p {
        margin: 0;
        color: var(--pm-muted);
        font-size: .95rem;
    }

    .permisos-page .pm-notice {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 26px;
        padding: 16px 20px;
        background: #fffbeb;
        border: 1px solid #fde7a3;
        border-radius: 12px;
        color: #856019;
        font-size: .88rem;
        line-height: 1.65;
    }

    .permisos-page .pm-notice-mark {
        display: inline-flex;
        flex: 0 0 24px;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: #fef0c3;
        border-radius: 50%;
        font-weight: 700;
    }

    .permisos-page .pm-card {
        margin-bottom: 30px;
        background: #fff;
        border: 1px solid var(--pm-border);
        border-radius: 16px;
        box-shadow: 0 6px 24px rgba(30, 41, 59, .05);
        overflow: hidden;
    }

    .permisos-page .pm-card-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 24px 28px;
        background: linear-gradient(115deg, #f5f7ff, #f8fafc);
        border-bottom: 1px solid var(--pm-border);
    }

    .permisos-page .pm-eyebrow {
        margin-bottom: 6px;
        color: var(--pm-primary);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
    }

    .permisos-page .pm-role-name {
        margin: 0;
        color: #1e293b;
        font-size: 1.35rem;
        font-weight: 700;
    }

    .permisos-page .pm-role-badge {
        padding: 7px 13px;
        background: #eae8ff;
        border: 1px solid #dedaff;
        border-radius: 999px;
        color: #5145b8;
        font-size: .75rem;
        font-weight: 600;
    }

    .permisos-page .pm-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .permisos-page .pm-table {
        width: 100%;
        min-width: 620px;
        margin: 0;
        border-collapse: collapse;
    }

    .permisos-page .pm-table thead th {
        padding: 14px 24px;
        background: #fff;
        border-bottom: 1px solid var(--pm-border);
        color: #718096;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .07em;
        text-transform: uppercase;
    }

    .permisos-page .pm-table thead th:first-child {
        width: 22%;
        text-align: center;
    }

    .permisos-page .pm-table thead th:last-child {
        text-align: left;
    }

    .permisos-page .pm-module {
        width: 22%;
        padding: 24px 18px;
        background: #f8faff;
        border-right: 1px solid var(--pm-border);
        border-bottom: 1px solid var(--pm-border);
        text-align: center;
        vertical-align: middle;
    }

    .permisos-page .pm-module-name {
        display: inline-block;
        color: #405276;
        font-size: .9rem;
        font-weight: 650;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .permisos-page .pm-permissions {
        padding: 8px 14px;
        border-bottom: 1px solid var(--pm-border);
        vertical-align: middle;
    }

    .permisos-page .pm-option {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin: 4px 0;
        padding: 12px 14px;
        border-radius: 9px;
        cursor: pointer;
        transition: background-color .15s ease;
    }

    .permisos-page .pm-option:hover {
        background: #f3f5fc;
    }

    .permisos-page .pm-option:has(input:checked) {
        background: #eef2ff;
    }

    .permisos-page .pm-option:has(input:focus-visible) {
        outline: 2px solid var(--pm-primary);
        outline-offset: 2px;
    }

    .permisos-page .pm-checkbox {
        appearance: auto;
        float: none;
        flex: 0 0 18px;
        width: 18px;
        height: 18px;
        margin: 2px 0 0;
        accent-color: var(--pm-primary);
        cursor: pointer;
    }

    .permisos-page .pm-option-text {
        min-width: 0;
        color: #64748b;
        font-size: .87rem;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .permisos-page .pm-permission-key {
        color: #334155;
        font-weight: 600;
    }

    .permisos-page .pm-separator {
        margin: 0 4px;
        color: #94a3b8;
    }

    .permisos-page .pm-option-disabled,
    .permisos-page .pm-option-disabled input {
        cursor: default;
    }

    .permisos-page .pm-option-disabled .pm-option-text {
        color: #8490a3;
    }

    .permisos-page .pm-table tbody tr:last-child > th,
    .permisos-page .pm-table tbody tr:last-child > td {
        border-bottom: 0;
    }

    .permisos-page .pm-card-footer {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 20px 28px;
        background: #fafbfe;
        border-top: 1px solid var(--pm-border);
    }

    .permisos-page .pm-footer-note {
        max-width: 440px;
        margin: 0;
        color: var(--pm-muted);
        font-size: .8rem;
        line-height: 1.6;
    }

    .permisos-page .pm-save-button,
    .pm-confirm-dialog .pm-confirm-button {
        padding: 11px 22px;
        background: #4f46e5;
        border: 1px solid #4f46e5;
        border-radius: 9px;
        color: #fff;
        font-size: .87rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color .15s ease, box-shadow .15s ease;
    }

    .permisos-page .pm-save-button:hover,
    .pm-confirm-dialog .pm-confirm-button:hover {
        background: #4338ca;
        border-color: #4338ca;
        color: #fff;
        box-shadow: 0 4px 12px rgba(79, 70, 229, .2);
    }

    .permisos-page .pm-save-button:focus-visible,
    .pm-confirm-dialog button:focus-visible {
        outline: 3px solid #a5b4fc;
        outline-offset: 3px;
    }

    .permisos-page .pm-save-button:disabled,
    .pm-confirm-dialog button:disabled {
        opacity: .65;
        cursor: wait;
    }

    .permisos-page .pm-empty {
        padding: 40px 24px;
        color: var(--pm-muted);
        text-align: center;
    }

    /* Ventana de confirmación */
    .pm-confirm-dialog {
        width: min(460px, calc(100% - 32px));
        max-height: calc(100dvh - 48px);
        margin: auto;
        padding: 0;
        background: #fff;
        border: 0;
        border-radius: 20px;
        box-shadow: 0 24px 80px rgba(15, 23, 42, .25);
        overflow-y: auto;
    }

    .pm-confirm-dialog::backdrop {
        background: rgba(15, 23, 42, .55);
        backdrop-filter: blur(4px);
    }

    .pm-confirm-dialog .pm-dialog-body {
        padding: 32px 28px 24px;
        text-align: center;
    }

    .pm-confirm-dialog .pm-dialog-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin: 0 auto 20px;
        background: #eef2ff;
        border: 8px solid #f7f8ff;
        border-radius: 50%;
        color: #4f46e5;
    }

    .pm-confirm-dialog .pm-dialog-icon svg {
        width: 26px;
        height: 26px;
    }

    .pm-confirm-dialog .pm-dialog-title {
        margin: 0 0 12px;
        color: #1e293b;
        font-size: 1.35rem;
        font-weight: 700;
    }

    .pm-confirm-dialog .pm-dialog-description {
        margin: 0;
        color: #64748b;
        font-size: .93rem;
        line-height: 1.75;
    }

    .pm-confirm-dialog .pm-dialog-role {
        color: #4f46e5;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .pm-confirm-dialog .pm-dialog-warning {
        margin-top: 20px;
        padding: 12px 14px;
        background: #fffbeb;
        border: 1px solid #fde7a3;
        border-radius: 10px;
        color: #856019;
        font-size: .8rem;
        line-height: 1.6;
    }

    .pm-confirm-dialog .pm-dialog-footer {
        display: flex;
        justify-content: center;
        gap: 12px;
        padding: 20px 28px;
        background: #f8fafc;
        border-top: 1px solid #e5eaf2;
    }

    .pm-confirm-dialog .pm-cancel-button {
        padding: 11px 22px;
        background: #fff;
        border: 1px solid #dbe1ea;
        border-radius: 9px;
        color: #475569;
        font-size: .87rem;
        font-weight: 600;
        cursor: pointer;
    }

    .pm-confirm-dialog .pm-cancel-button:hover {
        background: #eef2f7;
    }

    @media (max-width: 767px) {
        .permisos-page {
            padding: 22px 12px;
        }

        .permisos-page .pm-card-header,
        .permisos-page .pm-card-footer {
            padding: 20px;
        }

        .permisos-page .pm-card-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .permisos-page .pm-save-button {
            width: 100%;
            text-align: center;
        }

        .pm-confirm-dialog .pm-dialog-footer {
            flex-direction: column;
        }

        .pm-confirm-dialog .pm-dialog-footer button {
            width: 100%;
        }
    }
</style>

@php
    $puedeConfigurar = auth()->user()->can('permisos.configurar');
@endphp

<div class="permisos-page">
    <header class="pm-heading">
        <h1>Matriz de permisos</h1>
        <p>Administra el acceso a los módulos y las acciones de cada rol.</p>
    </header>

    @include('accesos.errores')

    <div class="pm-notice" role="alert">
        <span class="pm-notice-mark" aria-hidden="true">!</span>

        <div>
            <strong>Los cambios se aplican inmediatamente.</strong>
            Afectan a todos los usuarios del rol.
            Los módulos de usuarios, roles y permisos se reservan al SuperAdmin.
        </div>
    </div>

    @forelse($roles as $rol)
        <form
            method="POST"
            action="{{ route('accesos.permisos.guardar', $rol->id_rol) }}"
            class="pm-card js-permisos-form"
            data-rol-nombre="{{ $rol->nombre }}"
        >
            @csrf
            @method('PUT')

            <div class="pm-card-header">
                <div>
                    <div class="pm-eyebrow">Permisos del rol</div>
                    <h2 class="pm-role-name">{{ $rol->nombre }}</h2>
                </div>

                @if($rol->clave === 'superadmin')
                    <span class="pm-role-badge">SuperAdmin</span>
                @elseif(!$puedeConfigurar)
                    <span class="pm-role-badge">Solo lectura</span>
                @endif
            </div>

            <div
                class="pm-table-wrapper"
                role="region"
                aria-label="Permisos de {{ $rol->nombre }}"
                tabindex="0"
            >
                <table class="pm-table">
                    <caption class="visually-hidden">
                        Matriz de permisos del rol {{ $rol->nombre }}
                    </caption>

                    <thead>
                        <tr>
                            <th scope="col">Módulo</th>
                            <th scope="col">Permisos disponibles</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($grupos as $modulo => $permisos)
                            <tr>
                                <th scope="row" class="pm-module">
                                    <span class="pm-module-name">
                                        {{ ucfirst(str_replace('_', ' ', $modulo)) }}
                                    </span>
                                </th>

                                <td class="pm-permissions">
                                    @foreach($permisos as $permiso)
                                        @php
                                            $bloqueado = $rol->clave !== 'superadmin'
                                                && in_array($modulo, [
                                                    'usuarios',
                                                    'roles',
                                                    'permisos',
                                                ]);

                                            $deshabilitado = $bloqueado
                                                || !$puedeConfigurar;

                                            $seleccionado = !$bloqueado
                                                && $rol->permisos->contains(
                                                    fn($p) =>
                                                        $p->id_permiso === $permiso->id_permiso
                                                        && $p->pivot->activo
                                                );
                                        @endphp

                                        <label class="pm-option {{ $deshabilitado ? 'pm-option-disabled' : '' }}">
                                            <input
                                                type="checkbox"
                                                class="pm-checkbox"
                                                name="permisos[]"
                                                value="{{ $permiso->id_permiso }}"
                                                @checked($seleccionado)
                                                @disabled($deshabilitado)
                                            >

                                            <span class="pm-option-text">
                                                <span class="pm-permission-key">{{ $permiso->clave }}</span>
                                                <span class="pm-separator">—</span>
                                                {{ $permiso->descripcion }}
                                            </span>
                                        </label>
                                    @endforeach
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="pm-empty">
                                    No hay permisos disponibles.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @can('permisos.configurar')
                <div class="pm-card-footer">
                    <p class="pm-footer-note">
                        Revisa los permisos seleccionados antes de guardar.
                        Los cambios se aplicarán a todos los usuarios del rol.
                    </p>

                    <button type="submit" class="btn pm-save-button">
                        Guardar permisos
                        <span class="visually-hidden">
                            de {{ $rol->nombre }}
                        </span>
                    </button>
                </div>
            @endcan
        </form>
    @empty
        <div class="pm-card pm-empty">
            No hay roles disponibles para configurar.
        </div>
    @endforelse
</div>

{{-- Una sola ventana de confirmación para todos los roles --}}
<dialog
    id="pm-confirm-dialog"
    class="pm-confirm-dialog"
    aria-labelledby="pm-dialog-title"
    aria-describedby="pm-dialog-description pm-dialog-warning"
>
    <div class="pm-dialog-body">
        <div class="pm-dialog-icon" aria-hidden="true">
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path d="M9 12l2 2 4-4" />
                <path d="M12 3l8 4v5c0 5-8 9-8 9s-8-4-8-9V7l8-4z" />
            </svg>
        </div>

        <h2 id="pm-dialog-title" class="pm-dialog-title">
            ¿Guardar los permisos?
        </h2>

        <p id="pm-dialog-description" class="pm-dialog-description">
            Se actualizarán los permisos del rol
            <strong id="pm-dialog-role" class="pm-dialog-role"></strong>.
        </p>

        <div id="pm-dialog-warning" class="pm-dialog-warning">
            Los cambios afectarán inmediatamente a todos los usuarios
            que tengan asignado este rol.
        </div>
    </div>

    <div class="pm-dialog-footer">
        <button
            type="button"
            id="pm-cancel-button"
            class="pm-cancel-button"
            autofocus
        >
            Cancelar
        </button>

        <button
            type="button"
            id="pm-confirm-button"
            class="pm-confirm-button"
        >
            Sí, guardar
        </button>
    </div>
</dialog>

<script>
    (() => {
        const dialog = document.getElementById('pm-confirm-dialog');
        const roleName = document.getElementById('pm-dialog-role');
        const cancelButton = document.getElementById('pm-cancel-button');
        const confirmButton = document.getElementById('pm-confirm-button');

        let pendingForm = null;
        let pendingSubmitter = null;
        let returnFocus = null;
        let sending = false;

        document.querySelectorAll('.js-permisos-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                // Permite el envío únicamente después de confirmar.
                if (form.dataset.confirmed === 'true') {
                    return;
                }

                event.preventDefault();

                if (sending || dialog.open) {
                    return;
                }

                pendingForm = form;
                pendingSubmitter = event.submitter;
                returnFocus = event.submitter || document.activeElement;

                roleName.textContent = form.dataset.rolNombre;
                confirmButton.disabled = false;
                confirmButton.textContent = 'Sí, guardar';

                dialog.showModal();
            });
        });

        cancelButton.addEventListener('click', () => {
            dialog.close();
        });

        // Escape cancela sin enviar el formulario.
        dialog.addEventListener('cancel', () => {
            sending = false;
        });

        dialog.addEventListener('close', () => {
            pendingForm = null;
            pendingSubmitter = null;

            if (returnFocus && returnFocus.isConnected) {
                returnFocus.focus();
            }

            returnFocus = null;
        });

        confirmButton.addEventListener('click', () => {
            if (!pendingForm || sending) {
                return;
            }

            const form = pendingForm;
            const submitter = pendingSubmitter;

            if (!form.reportValidity()) {
                dialog.close();
                return;
            }

            sending = true;
            confirmButton.disabled = true;
            confirmButton.textContent = 'Guardando…';
            form.dataset.confirmed = 'true';

            // Mantiene el envío normal de Laravel con CSRF y método PUT.
            if (submitter) {
                form.requestSubmit(submitter);
            } else {
                form.requestSubmit();
            }

            dialog.close();

            form.querySelectorAll('button[type="submit"]').forEach((button) => {
                button.disabled = true;
                button.textContent = 'Guardando…';
            });
        });
    })();
</script>
@endsection
=======
@include('accesos.errores')
<div class="alert alert-warning">Los cambios afectan inmediatamente a todos los usuarios del rol. Solo se puede habilitar el borrado de clientes, proveedores, tarifas y catálogos sin relaciones. El historial se conserva. La seguridad se reserva al SuperAdmin.</div>
@foreach($roles as $rol)
<form method="POST" action="{{ route('accesos.permisos.guardar',$rol->id_rol) }}" class="card card-body mb-4" onsubmit="return confirm('¿Guardar los permisos para todos los usuarios de este rol?')">@csrf @method('PUT')
<h2 class="h4">{{ $rol->nombre }}</h2>
@foreach($grupos as $modulo=>$permisos)
<fieldset class="mb-3"><legend class="h6">{{ ucfirst($modulo) }}</legend>
@foreach($permisos as $permiso)
@php($bloqueado=($permiso->accion==='eliminar' && !in_array($permiso->clave,\App\Services\RegistroCrud::PERMISOS_BORRADO,true)) || ($rol->clave!=='superadmin' && in_array($modulo,['usuarios','roles','permisos'])))
<label class="d-block"><input type="checkbox" name="permisos[]" value="{{ $permiso->id_permiso }}" @checked(!$bloqueado && $rol->permisos->contains(fn($p)=>$p->id_permiso===$permiso->id_permiso && $p->pivot->activo)) @disabled($bloqueado || !auth()->user()->can('permisos.configurar'))> {{ $permiso->clave }} — {{ $permiso->descripcion }}</label>
@endforeach</fieldset>@endforeach
@can('permisos.configurar')<button class="btn btn-primary">Guardar permisos de {{ $rol->nombre }}</button>@endcan
</form>@endforeach
@endsection
>>>>>>> 7c138c03672fada061748929f85f1bfaebb206ab
