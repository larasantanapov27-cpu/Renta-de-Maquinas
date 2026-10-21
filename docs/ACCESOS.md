# Implementación de acceso por roles y permisos

Se adaptó el proyecto existente de Laravel 12.69.2 (PHP 8.2, MariaDB local en XAMPP). No se instalaron dependencias nuevas ni se modificó `.env`.

## Inspección y conservación

- Rutas en `routes/web.php`, middleware en `bootstrap/app.php`, modelos con llaves de negocio y vistas Blade con la plantilla San Juan.
- El login y registro existentes están en `app/Http/Controllers/login.php`; se conservaron sus validaciones de contraseña y el cierre de sesión con invalidación y regeneración del token.
- Conexión comprobada: controlador mysql, servidor local 127.0.0.1:3306, base `renta_maquinaria`. Se usó la configuración existente sin copiar credenciales a código.
- Se verificaron directamente columnas de `users`, `roles`, `permisos` y `rol_permiso`, el catálogo completo de permisos y el motor InnoDB de las tablas que intervienen en la continuidad de administración.
- Gabriel, usuario ID 1, estaba activo con `id_rol = 1`. No se actualizaron su cuenta ni contraseña.
- No se encontró una exportación SQL original dentro del proyecto. Se revisó la proporcionada en `C:/Users/laras/Downloads/renta_maquinaria.sql`; la base real prevalece sobre ese respaldo, cuyo usuario todavía figuraba pendiente.
- Se conservaron los cambios locales previos en rutas, plantilla, HomeController y home.blade.php. El nuevo acceso a `/panel` dirige a los paneles por rol; el código del inicio anterior se conserva.
- Se reutilizaron las vistas de módulos y el CSS de la plantilla. Las referencias CDN de Bootstrap de la plantilla se sustituyeron por los archivos existentes en `public/bootstrap`.
- No se ejecutaron migraciones, importaciones, GRANT, REVOKE ni operaciones de limpieza de datos reales. No fue necesario cambiar la estructura existente.

## Funcionalidades

- Registro público pendiente, sin rol, con hash de Laravel; los privilegios enviados por el navegador no se usan.
- Login con regeneración de sesión, validación de cuenta y rol activos y redirección por lista de rutas permitidas. Se descartan destinos intended que contradigan el panel del rol.
- Middleware `acceso`: consulta el estado actual del usuario en cada petición y comprueba los permisos actuales de `rol_permiso`. No se guardan permisos en sesión ni en caché persistente.
- Gates compartidos con Blade para mostrar acciones permitidas. La seguridad también se comprueba en el servidor.
- Paneles `superadmin.inicio`, `rentas.inicio`, `mantenimiento.inicio` y `administrativo.inicio`, con indicadores reales condicionados al permiso de consulta.
- Menús generados por permisos, incluso si se habilitan operaciones de negocio para otro rol. Para modificar, devolver o cambiar estado sin permiso de consulta, se ofrece un selector de operación por identificador sin revelar el listado.
- SuperAdmin: usuarios paginados, aprobación/rechazo, asignación de rol activo, activación/desactivación, estado de roles y matriz de permisos agrupada por módulo. La aprobación exige tanto validar como asignar rol. Desactivar y reactivar no permite eludir la aprobación de cuentas pendientes.
- La matriz afecta inmediatamente a todos los usuarios del rol y lo indica en pantalla. Los permisos de seguridad no pueden asignarse a roles de negocio. El SuperAdmin tampoco recibe acceso automático a los módulos de negocio.
- Continuidad: todas las mutaciones de seguridad bloquean roles, usuarios y pivotes en el mismo orden dentro de una transacción. Se revierte cualquier cambio que deje sin al menos un SuperAdmin activo con los ocho permisos esenciales.
- Clientes y proveedores gestionan sus datos de persona desde su propio formulario. No se acepta un ID arbitrario de persona. Al editar se crea una persona para ese contexto y se actualiza la relación, conservando los datos que pudiera compartir el otro contexto. No se elimina la persona anterior.
- El acceso general a personas sigue bloqueado. La implementación CRUD posterior permite habilitar permisos de borrado para clientes, proveedores, tarifas y catálogos; rechaza registros relacionados y conserva el historial. Véase `CRUD_TRANSACCIONES.md`.
- Catálogos limitados a los que necesita un módulo autorizado. Se agregaron listados de tipos de mantenimiento y motivos de baja.
- Rentas: registro de cabecera y varios detalles, precios y depósitos obtenidos de la tarifa vigente del tipo de máquina, validación de disponibilidad con bloqueos y transacción, consulta de detalles, edición de fecha programada/observaciones y devolución parcial o total.
- La devolución requiere `rentas.devolver`; actualiza `detalle_rentas.fecha_devolucion_real`, finaliza la renta cuando todos los detalles fueron devueltos y libera solo equipos sin otra ocupación.
- El cambio manual de estado requiere `maquinas.cambiar_estado`, incluso si se intenta mediante la modificación general. No se permite marcar disponible un equipo rentado, reservado, en mantenimiento abierto o de baja. Una baja definitiva no se reactiva.
- Mantenimientos y bajas comprueban ocupación bajo bloqueo y actualizan el estado del equipo como parte de su operación. Los cambios de varias tablas se ejecutan en transacciones.
- Se corrigió `DetalleRenta`: el campo real es `cargo_adicional`, no `cargo_retraso`.
- Validaciones, CSRF y métodos POST/PUT/PATCH para cambios. Página 403 con salida de sesión disponible para cuentas bloqueadas.

## Verificación

Última suite completa ejecutada: **28 pruebas, 228 assertions, sin fallos**, usando exclusivamente SQLite en memoria. Los fixtures contienen estructura adaptada a SQLite y catálogos; no se importan sesiones, contraseñas ni datos personales del respaldo real.

Cobertura: registro pendiente y campos de privilegio; bloqueo de estados no aprobados y roles inactivos; login y cuatro paneles; aprobación con rol; 403 y ausencia de bypass del SuperAdmin; activación/revocación de permisos; cuenta desactivada y cambio de rol durante sesión; último SuperAdmin; rechazo de permisos de seguridad para roles de negocio; catálogos por contexto; permisos especiales; personas compartidas; devoluciones y registro de detalles; transacciones de mantenimiento/baja; acciones ocultas; formularios y listados existentes; rutas de inicio manipuladas y aprobación indirecta por cambio de estado.

También se revisó la sintaxis de 55 archivos PHP y se ejecutó `git diff --check`.

## Ejecución

Con MariaDB de XAMPP encendido, desde la raíz del proyecto en PowerShell:

```powershell
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000
```

Abrir `http://127.0.0.1:8000/login` y usar la cuenta existente. No es necesario ejecutar migraciones para estos cambios. Los recursos de Bootstrap son locales.

Para repetir las pruebas aisladas:

```powershell
C:\xampp\php\php.exe vendor/phpunit/phpunit/phpunit --testdox
```

`phpunit.xml` ya configura SQLite `:memory:`, sesión array y caché array. La nueva suite comprueba esa conexión antes de preparar tablas y falla si no es la base de pruebas esperada.

## Alcance de la verificación

- Se comprobó lectura y estructura de MariaDB real. Las pruebas de escritura se ejecutaron únicamente en SQLite en memoria.
- La serialización usa bloqueos InnoDB, pero no se ejecutó una prueba de carga concurrente contra un MariaDB separado.
- Los formularios se renderizaron mediante pruebas HTTP de Laravel. No se hizo una revisión visual interactiva en navegador.
- No se implementó un libro contable nuevo para depósitos o reembolsos; se conserva el modelo de importes existente. Las eliminaciones permanecen deshabilitadas.
- El registro de rentas crea operaciones activas; no se agregó un flujo independiente para entregar reservas programadas.

## Archivos

La siguiente lista incluye las adaptaciones sobre archivos que ya tenían cambios locales. Los archivos HomeController.php, home.blade.php y rentas/create.blade.php ya existían sin seguimiento antes de este trabajo; no son archivos nuevos de esta implementación.

Creados:

- `app/Http/Controllers/AccesoController.php`
- `app/Http/Controllers/OperacionController.php`
- `app/Http/Controllers/PanelController.php`
- `app/Http/Middleware/AccesoVigente.php`
- `app/Models/Permiso.php`
- `app/Models/Rol.php`
- `app/Services/Acceso.php`
- `app/Services/ContinuidadAdmin.php`
- `app/Services/EstadoEquipo.php`
- `docs/ACCESOS.md`
- `resources/views/accesos/catalogo.blade.php`
- `resources/views/accesos/errores.blade.php`
- `resources/views/accesos/permisos.blade.php`
- `resources/views/accesos/persona.blade.php`
- `resources/views/accesos/roles.blade.php`
- `resources/views/accesos/usuarios.blade.php`
- `resources/views/errors/403.blade.php`
- `resources/views/maquinas/estado.blade.php`
- `resources/views/paneles/inicio.blade.php`
- `resources/views/paneles/operaciones.blade.php`
- `resources/views/rentas/detalles.blade.php`
- `resources/views/rentas/devolucion.blade.php`
- `resources/views/rentas/formulario.blade.php`
- `tests/Feature/AccesoTest.php`
- `tests/Fixtures/catalogos.sql`
- `tests/Fixtures/schema.sql`

Modificados:

- `app/Http/Controllers/BajaMaquinaController.php`
- `app/Http/Controllers/ClienteController.php`
- `app/Http/Controllers/ComprasController.php`
- `app/Http/Controllers/DetalleTarifaController.php`
- `app/Http/Controllers/DireccionClienteController.php`
- `app/Http/Controllers/EstadoMaquinaController.php`
- `app/Http/Controllers/EstadoRentaController.php`
- `app/Http/Controllers/MantenimientoController.php`
- `app/Http/Controllers/MaquinaController.php`
- `app/Http/Controllers/MetodoPagoController.php`
- `app/Http/Controllers/MotivoBajaController.php`
- `app/Http/Controllers/PagoController.php`
- `app/Http/Controllers/PeriodoRentaController.php`
- `app/Http/Controllers/ProveedorController.php`
- `app/Http/Controllers/RentaController.php`
- `app/Http/Controllers/TipoMantenimientoController.php`
- `app/Http/Controllers/TipoMaquinaController.php`
- `app/Http/Controllers/login.php`
- `app/Models/DetalleRenta.php`
- `app/Models/User.php`
- `app/Providers/AppServiceProvider.php`
- `bootstrap/app.php`
- `resources/views/bajas_maquinas/create.blade.php`
- `resources/views/bajas_maquinas/edit.blade.php`
- `resources/views/bajas_maquinas/index.blade.php`
- `resources/views/clientes/create.blade.php`
- `resources/views/clientes/edit.blade.php`
- `resources/views/clientes/index.blade.php`
- `resources/views/compras/create.blade.php`
- `resources/views/compras/edit.blade.php`
- `resources/views/compras/index.blade.php`
- `resources/views/detalle_tarifa/create.blade.php`
- `resources/views/detalle_tarifa/edit.blade.php`
- `resources/views/detalle_tarifa/index.blade.php`
- `resources/views/direcciones_clientes/create.blade.php`
- `resources/views/direcciones_clientes/edit.blade.php`
- `resources/views/direcciones_clientes/index.blade.php`
- `resources/views/estados_maquina/create.blade.php`
- `resources/views/estados_maquina/edit.blade.php`
- `resources/views/estados_maquina/index.blade.php`
- `resources/views/estados_renta/create.blade.php`
- `resources/views/estados_renta/edit.blade.php`
- `resources/views/estados_renta/index.blade.php`
- `resources/views/layouts/template.blade.php`
- `resources/views/mantenimientos/create.blade.php`
- `resources/views/mantenimientos/edit.blade.php`
- `resources/views/mantenimientos/index.blade.php`
- `resources/views/maquinas/create.blade.php`
- `resources/views/maquinas/edit.blade.php`
- `resources/views/maquinas/index.blade.php`
- `resources/views/metodos_pago/create.blade.php`
- `resources/views/metodos_pago/edit.blade.php`
- `resources/views/metodos_pago/index.blade.php`
- `resources/views/motivos_baja/create.blade.php`
- `resources/views/motivos_baja/edit.blade.php`
- `resources/views/pagos/create.blade.php`
- `resources/views/pagos/edit.blade.php`
- `resources/views/pagos/index.blade.php`
- `resources/views/pagos/renta.blade.php`
- `resources/views/periodos_renta/create.blade.php`
- `resources/views/periodos_renta/edit.blade.php`
- `resources/views/periodos_renta/index.blade.php`
- `resources/views/personas/create.blade.php`
- `resources/views/personas/edit.blade.php`
- `resources/views/personas/index.blade.php`
- `resources/views/proveedores/create.blade.php`
- `resources/views/proveedores/edit.blade.php`
- `resources/views/proveedores/index.blade.php`
- `resources/views/rentas/index.blade.php`
- `resources/views/tipos_mantenimiento/create.blade.php`
- `resources/views/tipos_mantenimiento/edit.blade.php`
- `resources/views/tipos_maquina/create.blade.php`
- `resources/views/tipos_maquina/edit.blade.php`
- `resources/views/tipos_maquina/index.blade.php`
- `routes/web.php`
- `tests/Feature/ExampleTest.php`
