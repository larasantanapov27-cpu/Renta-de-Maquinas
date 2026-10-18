CREATE TABLE `bajas_maquinas` (
  `id_baja` INTEGER PRIMARY KEY,
  `id_maquina` INTEGER NOT NULL,
  `id_motivo_baja` INTEGER NOT NULL,
  `fecha_baja` date NOT NULL,
  `descripcion` text DEFAULT NULL
);
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` INTEGER NOT NULL
,
 PRIMARY KEY (`key`));
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` INTEGER NOT NULL
,
 PRIMARY KEY (`key`));
CREATE TABLE `clientes` (
  `id_cliente` INTEGER PRIMARY KEY,
  `id_persona` INTEGER NOT NULL,
  `correo` varchar(150) DEFAULT NULL
);
CREATE TABLE `compras` (
  `id_compra` INTEGER PRIMARY KEY,
  `id_proveedor` INTEGER NOT NULL,
  `id_maquina` INTEGER NOT NULL,
  `fecha_compra` date NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `numero_factura` varchar(100) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
);
CREATE TABLE `detalle_rentas` (
  `id_detalle_renta` INTEGER PRIMARY KEY,
  `id_renta` INTEGER NOT NULL,
  `id_maquina` INTEGER NOT NULL,
  `id_tarifa` INTEGER NOT NULL,
  `cantidad_periodos` decimal(10,2) NOT NULL,
  `precio_aplicado` decimal(10,2) NOT NULL,
  `deposito_aplicado` decimal(10,2) NOT NULL DEFAULT 0.00,
  `descuento` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cargo_adicional` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cargo_danio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_devolucion_real` datetime DEFAULT NULL,
  `observaciones` text DEFAULT NULL
);
CREATE TABLE `detalle_tarifa` (
  `id_tarifa` INTEGER PRIMARY KEY,
  `id_tipo_maquina` INTEGER NOT NULL,
  `id_periodo` INTEGER NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `deposito` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL
);
CREATE TABLE `direcciones_clientes` (
  `id_direccion` INTEGER PRIMARY KEY,
  `id_cliente` INTEGER NOT NULL,
  `calle` varchar(150) NOT NULL,
  `numero_exterior` varchar(20) DEFAULT NULL,
  `numero_interior` varchar(20) DEFAULT NULL,
  `colonia` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `referencia` varchar(255) DEFAULT NULL
);
CREATE TABLE `estados_maquina` (
  `id_estado_maquina` INTEGER PRIMARY KEY,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
);
CREATE TABLE `estados_renta` (
  `id_estado_renta` INTEGER PRIMARY KEY,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
);
CREATE TABLE `failed_jobs` (
  `id` INTEGER PRIMARY KEY,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE `jobs` (
  `id` INTEGER PRIMARY KEY,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` INTEGER NOT NULL,
  `reserved_at` INTEGER DEFAULT NULL,
  `available_at` INTEGER NOT NULL,
  `created_at` INTEGER NOT NULL
);
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` INTEGER NOT NULL,
  `pending_jobs` INTEGER NOT NULL,
  `failed_jobs` INTEGER NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` INTEGER DEFAULT NULL,
  `created_at` INTEGER NOT NULL,
  `finished_at` INTEGER DEFAULT NULL
,
 PRIMARY KEY (`id`));
CREATE TABLE `mantenimientos` (
  `id_mantenimiento` INTEGER PRIMARY KEY,
  `id_maquina` INTEGER NOT NULL,
  `id_tipo_mantenimiento` INTEGER NOT NULL,
  `fecha_entrada` datetime NOT NULL,
  `fecha_salida` datetime DEFAULT NULL,
  `descripcion` text NOT NULL,
  `costo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `observaciones` text DEFAULT NULL
);
CREATE TABLE `maquinas` (
  `id_maquina` INTEGER PRIMARY KEY,
  `codigo` varchar(30) NOT NULL,
  `id_tipo_maquina` INTEGER NOT NULL,
  `id_estado_maquina` INTEGER NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `numero_serie` varchar(100) DEFAULT NULL,
  `fecha_ingreso` date NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
);
CREATE TABLE `metodos_pago` (
  `id_metodo_pago` INTEGER PRIMARY KEY,
  `nombre` varchar(50) NOT NULL
);
CREATE TABLE `migrations` (
  `id` INTEGER PRIMARY KEY,
  `migration` varchar(255) NOT NULL,
  `batch` INTEGER NOT NULL
);
CREATE TABLE `motivos_baja` (
  `id_motivo_baja` INTEGER PRIMARY KEY,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
);
CREATE TABLE `pagos` (
  `id_pago` INTEGER PRIMARY KEY,
  `id_renta` INTEGER NOT NULL,
  `id_metodo_pago` INTEGER NOT NULL,
  `fecha_pago` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `monto` decimal(10,2) NOT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  `observaciones` varchar(255) DEFAULT NULL
);
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
,
 PRIMARY KEY (`email`));
CREATE TABLE `periodos_renta` (
  `id_periodo` INTEGER PRIMARY KEY,
  `nombre` varchar(50) NOT NULL
);
CREATE TABLE `permisos` (
  `id_permiso` INTEGER PRIMARY KEY,
  `clave` varchar(100) NOT NULL,
  `modulo` varchar(50) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL
);
CREATE TABLE `personas` (
  `id_persona` INTEGER PRIMARY KEY,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `telefono` varchar(20) NOT NULL
);
CREATE TABLE `proveedores` (
  `id_proveedor` INTEGER PRIMARY KEY,
  `id_persona` INTEGER NOT NULL,
  `rfc` varchar(13) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL
);
CREATE TABLE `rentas` (
  `id_renta` INTEGER PRIMARY KEY,
  `id_cliente` INTEGER NOT NULL,
  `id_estado_renta` INTEGER NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_salida` datetime NOT NULL,
  `fecha_devolucion_programada` datetime NOT NULL,
  `observaciones` text DEFAULT NULL
);
CREATE TABLE `roles` (
  `id_rol` INTEGER PRIMARY KEY,
  `clave` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `ruta_inicio` varchar(100) NOT NULL,
  `activo` INTEGER NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
);
CREATE TABLE `rol_permiso` (
  `id_rol` INTEGER NOT NULL,
  `id_permiso` INTEGER NOT NULL,
  `activo` INTEGER NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
,
 PRIMARY KEY (`id_rol`,`id_permiso`));
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` INTEGER DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` INTEGER NOT NULL
,
 PRIMARY KEY (`id`));
CREATE TABLE `tipos_mantenimiento` (
  `id_tipo_mantenimiento` INTEGER PRIMARY KEY,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
);
CREATE TABLE `tipos_maquina` (
  `id_tipo_maquina` INTEGER PRIMARY KEY,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
);
CREATE TABLE `users` (
  `id` INTEGER PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_rol` INTEGER DEFAULT NULL,
  `estado` TEXT NOT NULL DEFAULT 'pendiente'
);
