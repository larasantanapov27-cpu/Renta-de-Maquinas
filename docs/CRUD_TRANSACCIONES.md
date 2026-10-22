# CRUD y transacciones SQL explícitas

Implementación directa sobre Laravel/Blade y la base existente de XAMPP. Se conservaron la estructura de tablas, las claves, los roles y los archivos Bootstrap locales. No se ejecutaron migraciones ni cambios en registros reales.

## Módulos y botones

| Módulos | Acciones disponibles según permisos |
|---|---|
| Clientes, proveedores, direcciones de clientes y tarifas | Crear, Ver, Editar y Borrar si no existen relaciones |
| Tipos de máquina, periodos de renta, métodos de pago, tipos de mantenimiento y motivos de baja | Crear, Ver, Editar y Borrar si no existen relaciones |
| Estados de máquina y estados de renta | Crear, Ver y Editar descripción; se conservan los nombres operativos y se impide el borrado |
| Maquinaria | Crear, Ver, Editar y Cambiar estado; su retiro se registra en Bajas |
| Rentas | Crear con varios equipos, Ver datos y relaciones, Editar fecha programada/observaciones y Devolver parcial o totalmente |
| Compras, pagos, mantenimientos y bajas | Crear, Ver y Editar; se conserva el historial y se rechaza DELETE |

Los formularios existentes de Crear y Editar se conservaron y se verificaron por HTTP contra sus rutas reales. Se corrigió la plantilla inexistente en editar tipo de mantenimiento. Los detalles muestran todos los campos del registro, los datos de persona propios de clientes/proveedores y relaciones de módulos cuyo permiso de consulta esté activo. Los nombres y teléfonos de los clientes/proveedores relacionados se incluyen con su autorización de consulta. No se expone el módulo general de personas.

Los botones Borrar tienen confirmación del navegador, CSRF y método DELETE. El servidor comprueba autorización y referencias antes de borrar; las claves foráneas InnoDB también protegen frente a una inserción relacionada concurrente. Los estados operativos y registros históricos no se borran; no se inventó una columna de baja lógica. El retiro definitivo de maquinaria utiliza la tabla real `bajas_maquinas` y el estado Baja.

### Permisos

Se reutilizan `consultar`, `registrar`, `modificar`, `eliminar`, `devolver` y `cambiar_estado` ya existentes. Direcciones usa los permisos de clientes; tarifas usa `tarifas`; los catálogos conservan sus restricciones por módulo padre. El middleware comprueba los permisos actuales en cada petición, además de los Gates en Blade.

En **Accesos → Permisos**, el SuperAdmin puede habilitar `clientes.eliminar`, `proveedores.eliminar`, `tarifas.eliminar` y `catalogos.eliminar`. Los permisos de borrado del historial siguen bloqueados tanto en la matriz como en el servidor. No se activó ningún permiso en los roles reales. Para visualizar los botones de un módulo deben estar habilitados los permisos correspondientes y, para su listado, consultar.

## Tres operaciones transaccionales diferentes

| Operación y ubicación | Tablas modificadas | Reglas y bloqueos dentro de la transacción |
|---|---|---|
| Registrar renta: `RentaController::store`, POST `/rentas` | `rentas`, `detalle_rentas`, `maquinas` | Cliente aún existente, máquinas bloqueadas por ID en orden, disponibilidad/ocupación actual, tarifa bloqueada correspondiente al tipo y vigente en la salida, estados Activa y Rentada existentes. Precios y depósitos provienen del servidor. |
| Registrar devolución: `RentaController::devolver`, PATCH `/rentas/{id}/devolver` | `detalle_rentas`, `maquinas`, `rentas` | Bloquea primero máquinas en orden y después la renta y sus detalles; exige renta activa, fecha válida y detalles propios sin devolución previa. Libera solo máquinas sin otra ocupación y finaliza únicamente al devolver todos los equipos. |
| Registrar o cerrar mantenimiento: `MantenimientoController::guardar`, POST `/mantenimientos` y PUT `/mantenimientos/{id}` | `mantenimientos`, `maquinas` | Máquina y tipo existentes bajo bloqueo, sin renta/reserva pendiente, otro mantenimiento abierto ni baja definitiva. Impide trasladar un mantenimiento a otra máquina. Actualiza Mantenimiento o Disponible según la salida. |

Registrar y editar/cerrar mantenimiento pertenecen a la tercera operación; **no se cuentan como dos transacciones diferentes**.

Los datos de entrada se validan antes de abrir cada transacción. Las condiciones que dependen de la base se vuelven a comprobar dentro de ella. Todas las consultas, modelos y escrituras utilizan la conexión predeterminada del proyecto y su misma sesión PDO; se impide continuar con una reconexión a mitad de la operación.

### Instrucciones SQL exigidas

`app/Services/TransaccionSql.php`, método `ejecutar`, ejecuta literalmente:

```php
$db->unprepared('START TRANSACTION'); // rama MySQL/MariaDB
$resultado = $operacion();
$db->unprepared('COMMIT');
// ante cualquier excepción tras el inicio:
$db->unprepared('ROLLBACK');
```

En el código, el inicio selecciona `START TRANSACTION` para MySQL/MariaDB; `BEGIN TRANSACTION` solo se admite para la suite SQLite en ambiente testing. Las evidencias entregadas se ejecutaron en **MySQL/InnoDB con START TRANSACTION real**, además de la suite SQLite.

Antes de iniciar se consulta `information_schema.TABLES` y se rechaza cualquier tabla involucrada que no sea InnoDB o no exista. Las tablas reales se comprobaron mediante `SHOW TABLE STATUS`; no fue necesario convertir motores. No hay DDL, COMMIT implícito ni transacciones anidadas dentro de estas tres operaciones. `AccesoVigente` excluye expresamente estas cuatro rutas de su envoltura automática; las demás operaciones existentes conservan su manejo anterior.

Solo se confirma tras terminar todas las modificaciones. Una validación de negocio dentro de la operación revierte y muestra un error comprensible. Los fallos técnicos se registran mediante `Log::error` en el canal configurado de Laravel, normalmente `storage/logs/laravel.log`; se muestra un mensaje general sin SQL, credenciales ni trazas.

## Pruebas y evidencias

La prueba `tests/Feature/OperacionesCrudTest.php` envía solicitudes HTTP a las rutas y controladores reales con usuarios y datos sintéticos. Comprueba creación, lectura, edición y eliminación admitida, persistencia de campos, autorización del borrado, rechazo por relaciones, protección del historial y renderizado de listas y formularios. También valida que los depósitos formen parte del total mostrado en pagos por renta.

### Ejecución segura

Desde la raíz del proyecto:

```powershell
# Suite de regresión: SQLite en memoria, según phpunit.xml.
php vendor/phpunit/phpunit/phpunit

# Pruebas contra MySQL real con la configuración local existente.
php tests/mysql.php
```

`tests/mysql.php` es exclusivamente CLI. Lee `SHOW CREATE TABLE` de las tablas reales, crea `renta_pruebas_<12 caracteres hexadecimales>` y copia **solo la estructura**, incluidas las claves foráneas. Carga los catálogos de fixtures y datos de prueba; nunca copia clientes, pagos, usuarios ni otro contenido real. Comprueba el prefijo de la base antes de que las pruebas borren fixtures. Las modificaciones de prueba ocurren solo en esa base, y la elimina en un `finally`. Compara `CHECKSUM TABLE` del origen antes y después para comprobar que sus registros siguen intactos. Se requieren permisos locales para crear y eliminar únicamente la base temporal.

Cada uno de los seis casos transaccionales prepara sus propios datos. La máquina y el cliente sintéticos usan ID 9001; los AUTO_INCREMENT de los registros nuevos pueden variar. Se consultan los ID generados sin depender de números específicos.

| Operación | Caso exitoso | Fallo controlado, únicamente en tests |
|---|---|---|
| Registrar renta | Persiste una cabecera, un detalle con tarifa aplicada y máquina Rentada; se observa COMMIT. | Un listener de prueba lanza una excepción después de insertar el detalle, cuando la cabecera ya fue escrita. ROLLBACK deja sin cabecera ni detalles nuevos y conserva el estado inicial de la máquina. |
| Devolver | Guarda fecha real, marca máquina Disponible y renta Finalizada; se observa COMMIT. | El listener falla después del UPDATE del detalle. ROLLBACK conserva fecha real nula, estado Rentada y renta Activa. |
| Mantenimiento | Guarda mantenimiento abierto y estado Mantenimiento; se observa COMMIT. El flujo CRUD adicional también comprueba mantenimiento cerrado y Disponible. | El listener falla después del INSERT del mantenimiento. ROLLBACK elimina el registro recién escrito y conserva Disponible. |

Los fallos controlados se inyectan desde el proceso PHPUnit mediante eventos SQL, después de la escritura ejecutada. No existen rutas, parámetros ni botones públicos para provocarlos. En cada caso de fallo se compara la igualdad exacta de todas las filas de las cuatro tablas modificadas por las operaciones; en éxito se comprueban todos los cambios esperados y la sentencia COMMIT. También se verifica que ROLLBACK y COMMIT no aparezcan juntos en el mismo caso.

### Archivos de evidencia

- `docs/evidencia-transacciones.jsonl`: seis entradas, con operación, resultado, capturas completas antes/después y SQL realmente ejecutado (incluye START TRANSACTION, bloqueos FOR UPDATE y COMMIT/ROLLBACK). Cada línea es un objeto JSON.
- `docs/motores-pruebas.json`: motores de las tablas clonadas de la estructura real; todos InnoDB.
- `docs/relaciones-verificadas.json`: claves foráneas consultadas en la base original, sin datos personales.
- `docs/consultas-verificacion.sql`: consultas de lectura de motores, renta/detalles, devolución/estados y mantenimiento. Incluye las cuatro consultas de captura completa usadas antes y después.

El script regenera las evidencias al ejecutarse. La base temporal ya no existe al finalizar: las capturas persistentes están en el JSONL. Para una comprobación manual, utiliza una copia de desarrollo aislada, ejecuta las consultas antes y después y registra datos equivalentes desde los formularios.

### Recorrido manual

1. Con un usuario activo y permisos de consulta/registro/modificación del módulo, abre su listado, pulsa Crear, guarda y abre Ver. Comprueba los datos y relaciones; abre Editar y confirma la persistencia de los cambios. Las validaciones deben mostrarse sin perder el contexto de la aplicación.
2. Habilita el permiso eliminar apropiado desde la matriz del SuperAdmin. Crea un catálogo sin relaciones, pulsa Borrar, cancela una vez y confirma después. Debe conservarse al cancelar y desaparecer al confirmar. Intenta borrar un tipo de máquina utilizado: debe explicarse la relación que impide hacerlo. Un usuario sin eliminar debe recibir 403 incluso enviando DELETE directamente.
3. Registra una renta con máquina Disponible y tarifa vigente del mismo tipo. Verifica cabecera/detalles y estado Rentada con las consultas. Devuelve el detalle desde Rentas → Devolver y comprueba fecha real, Disponible y Finalizada. Una devolución parcial conserva la renta activa hasta el último detalle.
4. Registra un mantenimiento abierto para otra máquina libre. Comprueba el registro y estado Mantenimiento; edítalo agregando salida válida y comprueba Disponible. No debe aceptarse un equipo rentado o de baja.
5. Ejecuta `php tests/mysql.php` para reproducir automáticamente los tres fallos después de escritura y verificar la reversión completa. No se deben provocar errores sobre información real.

## Archivos modificados y creados

La lista completa se incluye a continuación; los controladores mantienen sus rutas, formularios y modelos existentes. Los cambios compartidos se concentran en el middleware y los servicios CRUD/transaccional.

- `app/Http/Controllers/AccesoController.php`
- `app/Http/Controllers/BajaMaquinaController.php`
- `app/Http/Controllers/ClienteController.php`
- `app/Http/Controllers/ComprasController.php`
- `app/Http/Controllers/Controller.php`
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
- `app/Http/Middleware/AccesoVigente.php`
- `app/Services/RegistroCrud.php`
- `app/Services/TransaccionSql.php`
- `docs/ACCESOS.md`
- `docs/CRUD_TRANSACCIONES.md`
- `docs/consultas-verificacion.sql`
- `docs/evidencia-transacciones.jsonl`
- `docs/motores-pruebas.json`
- `docs/relaciones-verificadas.json`
- `resources/views/accesos/catalogo.blade.php`
- `resources/views/accesos/permisos.blade.php`
- `resources/views/bajas_maquinas/index.blade.php`
- `resources/views/clientes/index.blade.php`
- `resources/views/compras/index.blade.php`
- `resources/views/crud/acciones.blade.php`
- `resources/views/crud/detalle.blade.php`
- `resources/views/detalle_tarifa/index.blade.php`
- `resources/views/direcciones_clientes/index.blade.php`
- `resources/views/estados_maquina/index.blade.php`
- `resources/views/estados_renta/index.blade.php`
- `resources/views/mantenimientos/index.blade.php`
- `resources/views/maquinas/index.blade.php`
- `resources/views/metodos_pago/index.blade.php`
- `resources/views/pagos/index.blade.php`
- `resources/views/periodos_renta/index.blade.php`
- `resources/views/proveedores/index.blade.php`
- `resources/views/tipos_mantenimiento/edit.blade.php`
- `resources/views/tipos_maquina/index.blade.php`
- `routes/web.php`
- `tests/Feature/AccesoTest.php`
- `tests/Feature/OperacionesCrudTest.php`
- `tests/mysql.php`

## Resultado de la verificación

- Suite completa SQLite en memoria: **37 pruebas, 537 aserciones aprobadas**.
- Suite CRUD/transacciones MySQL con estructura y claves foráneas reales: **9 pruebas, 305 aserciones aprobadas**.
- Tres casos COMMIT y tres ROLLBACK después de una escritura, con capturas verificadas.
- CHECKSUM TABLE del origen sin cambios y base temporal eliminada al finalizar.
- No se ejecutó una prueba de carga con solicitudes simultáneas; la protección se implementa con bloqueos FOR UPDATE, orden de bloqueo y claves foráneas InnoDB.
- La confirmación de Borrar utiliza confirm() del navegador. Las pruebas HTTP comprueban su presencia y la eliminación en servidor; no se automatizó el clic en un navegador.
