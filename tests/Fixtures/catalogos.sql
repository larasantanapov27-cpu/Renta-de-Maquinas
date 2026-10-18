INSERT INTO `permisos` (`id_permiso`, `clave`, `modulo`, `accion`, `descripcion`) VALUES
(1, 'usuarios.consultar', 'usuarios', 'consultar', 'Consultar las cuentas registradas'),
(2, 'usuarios.validar', 'usuarios', 'validar', 'Aprobar o rechazar cuentas pendientes'),
(3, 'usuarios.cambiar_estado', 'usuarios', 'cambiar_estado', 'Activar o desactivar cuentas'),
(4, 'usuarios.asignar_rol', 'usuarios', 'asignar_rol', 'Asignar o cambiar el rol de un usuario'),
(5, 'roles.consultar', 'roles', 'consultar', 'Consultar los roles disponibles'),
(6, 'roles.cambiar_estado', 'roles', 'cambiar_estado', 'Activar o desactivar un rol'),
(7, 'permisos.consultar', 'permisos', 'consultar', 'Consultar el catálogo y la configuración de permisos'),
(8, 'permisos.configurar', 'permisos', 'configurar', 'Activar o desactivar permisos de los roles'),
(9, 'clientes.consultar', 'clientes', 'consultar', 'consultar información de clientes'),
(10, 'clientes.registrar', 'clientes', 'registrar', 'registrar información de clientes'),
(11, 'clientes.modificar', 'clientes', 'modificar', 'modificar información de clientes'),
(12, 'clientes.eliminar', 'clientes', 'eliminar', 'eliminar información de clientes'),
(13, 'maquinas.consultar', 'maquinas', 'consultar', 'consultar información de maquinas'),
(14, 'maquinas.registrar', 'maquinas', 'registrar', 'registrar información de maquinas'),
(15, 'maquinas.modificar', 'maquinas', 'modificar', 'modificar información de maquinas'),
(16, 'maquinas.eliminar', 'maquinas', 'eliminar', 'eliminar información de maquinas'),
(17, 'rentas.consultar', 'rentas', 'consultar', 'consultar información de rentas'),
(18, 'rentas.registrar', 'rentas', 'registrar', 'registrar información de rentas'),
(19, 'rentas.modificar', 'rentas', 'modificar', 'modificar información de rentas'),
(20, 'rentas.eliminar', 'rentas', 'eliminar', 'eliminar información de rentas'),
(21, 'pagos.consultar', 'pagos', 'consultar', 'consultar información de pagos'),
(22, 'pagos.registrar', 'pagos', 'registrar', 'registrar información de pagos'),
(23, 'pagos.modificar', 'pagos', 'modificar', 'modificar información de pagos'),
(24, 'pagos.eliminar', 'pagos', 'eliminar', 'eliminar información de pagos'),
(25, 'tarifas.consultar', 'tarifas', 'consultar', 'consultar información de tarifas'),
(26, 'tarifas.registrar', 'tarifas', 'registrar', 'registrar información de tarifas'),
(27, 'tarifas.modificar', 'tarifas', 'modificar', 'modificar información de tarifas'),
(28, 'tarifas.eliminar', 'tarifas', 'eliminar', 'eliminar información de tarifas'),
(29, 'mantenimientos.consultar', 'mantenimientos', 'consultar', 'consultar información de mantenimientos'),
(30, 'mantenimientos.registrar', 'mantenimientos', 'registrar', 'registrar información de mantenimientos'),
(31, 'mantenimientos.modificar', 'mantenimientos', 'modificar', 'modificar información de mantenimientos'),
(32, 'mantenimientos.eliminar', 'mantenimientos', 'eliminar', 'eliminar información de mantenimientos'),
(33, 'proveedores.consultar', 'proveedores', 'consultar', 'consultar información de proveedores'),
(34, 'proveedores.registrar', 'proveedores', 'registrar', 'registrar información de proveedores'),
(35, 'proveedores.modificar', 'proveedores', 'modificar', 'modificar información de proveedores'),
(36, 'proveedores.eliminar', 'proveedores', 'eliminar', 'eliminar información de proveedores'),
(37, 'compras.consultar', 'compras', 'consultar', 'consultar información de compras'),
(38, 'compras.registrar', 'compras', 'registrar', 'registrar información de compras'),
(39, 'compras.modificar', 'compras', 'modificar', 'modificar información de compras'),
(40, 'compras.eliminar', 'compras', 'eliminar', 'eliminar información de compras'),
(41, 'bajas_maquinas.consultar', 'bajas_maquinas', 'consultar', 'consultar información de bajas_maquinas'),
(42, 'bajas_maquinas.registrar', 'bajas_maquinas', 'registrar', 'registrar información de bajas_maquinas'),
(43, 'bajas_maquinas.modificar', 'bajas_maquinas', 'modificar', 'modificar información de bajas_maquinas'),
(44, 'bajas_maquinas.eliminar', 'bajas_maquinas', 'eliminar', 'eliminar información de bajas_maquinas'),
(45, 'catalogos.consultar', 'catalogos', 'consultar', 'consultar información de catalogos'),
(46, 'catalogos.registrar', 'catalogos', 'registrar', 'registrar información de catalogos'),
(47, 'catalogos.modificar', 'catalogos', 'modificar', 'modificar información de catalogos'),
(48, 'catalogos.eliminar', 'catalogos', 'eliminar', 'eliminar información de catalogos'),
(72, 'rentas.devolver', 'rentas', 'devolver', 'Registrar la devolución de maquinaria'),
(73, 'maquinas.cambiar_estado', 'maquinas', 'cambiar_estado', 'Cambiar el estado operativo de una máquina');
INSERT INTO `roles` (`id_rol`, `clave`, `nombre`, `descripcion`, `ruta_inicio`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'SuperAdmi', 'Valida usuarios, asigna roles y administra permisos.', 'superadmin.inicio', 1, '2026-10-18 05:32:33', '2026-10-18 05:32:33'),
(2, 'rentas', 'Personal de rentas', 'Gestiona clientes, rentas, devoluciones y cobros.', 'rentas.inicio', 1, '2026-10-18 05:32:33', '2026-10-18 05:32:33'),
(3, 'mantenimiento', 'Personal de mantenimiento', 'Registra y consulta servicios de mantenimiento.', 'mantenimiento.inicio', 1, '2026-10-18 05:32:33', '2026-10-18 05:32:33'),
(4, 'administrativo', 'Personal administrativo', 'Gestiona compras, proveedores, pagos y tarifas.', 'administrativo.inicio', 1, '2026-10-18 05:32:33', '2026-10-18 05:32:33');
INSERT INTO `estados_maquina` (`id_estado_maquina`, `nombre`, `descripcion`) VALUES
(1, 'Disponible', 'La maquina se encuentra disponible para una nueva renta'),
(2, 'Rentada', 'La maquina se encuentra actualmente asignada a una renta'),
(3, 'Mantenimiento', 'La maquina se encuentra temporalmente fuera de servicio por mantenimiento'),
(4, 'Fuera de servicio', 'La maquina presenta una condicion que impide temporalmente su uso'),
(5, 'Baja', 'La maquina fue retirada definitivamente del servicio');
INSERT INTO `estados_renta` (`id_estado_renta`, `nombre`, `descripcion`) VALUES
(1, 'Programada', 'La renta fue registrada pero la maquinaria aun no ha sido entregada'),
(2, 'Activa', 'La maquinaria fue entregada y la renta se encuentra en curso'),
(3, 'Finalizada', 'La maquinaria fue devuelta y la renta concluyo correctamente'),
(4, 'Cancelada', 'La operacion fue cancelada antes de concluir el proceso');
INSERT INTO `periodos_renta` (`id_periodo`, `nombre`) VALUES
(1, 'Dia'),
(2, 'Hora'),
(3, 'Semana');
INSERT INTO `tipos_maquina` (`id_tipo_maquina`, `nombre`, `descripcion`) VALUES
(1, 'Revolvedora', 'Equipo utilizado para mezclar concreto y materiales de construccion'),
(2, 'Taladro', 'Herramienta utilizada para realizar perforaciones en diferentes superficies'),
(3, 'Vibrador para concreto', 'Equipo utilizado para compactar concreto fresco y eliminar espacios de aire'),
(4, 'Podadora', 'Equipo utilizado para el corte y mantenimiento de pasto'),
(5, 'Mosco podador', 'Equipo utilizado para trabajos de poda y corte de vegetacion'),
(6, 'Planta de luz', 'Equipo utilizado para suministrar energia electrica de manera temporal'),
(7, 'Aspiradora industrial', 'Equipo utilizado para la limpieza y recoleccion de residuos en areas de trabajo'),
(8, 'Compresor de aire', 'Equipo utilizado para suministrar aire comprimido a herramientas y trabajos diversos'),
(9, 'Cortadora', 'Equipo utilizado para realizar cortes en materiales de construccion'),
(10, 'Bailarina compactadora', 'Equipo utilizado para compactar suelo en espacios reducidos');
INSERT INTO `tipos_mantenimiento` (`id_tipo_mantenimiento`, `nombre`, `descripcion`) VALUES
(1, 'Preventivo', 'Servicio programado para conservar la maquina en buenas condiciones'),
(2, 'Correctivo', 'Reparacion realizada para corregir una falla detectada'),
(3, 'Revision general', 'Inspeccion del funcionamiento y estado general de la maquina');
INSERT INTO `motivos_baja` (`id_motivo_baja`, `nombre`, `descripcion`) VALUES
(1, 'Desgaste', 'La maquina alcanzo un nivel de desgaste que impide continuar utilizandola'),
(2, 'Falla irreparable', 'La maquina presenta una falla cuya reparacion no es viable'),
(3, 'Obsolescencia', 'El equipo fue retirado debido a su antiguedad u obsolescencia'),
(4, 'Venta', 'La maquina fue retirada del inventario debido a su venta'),
(5, 'Perdida', 'El equipo fue retirado del inventario debido a su perdida');
INSERT INTO `metodos_pago` (`id_metodo_pago`, `nombre`) VALUES
(1, 'Efectivo');
