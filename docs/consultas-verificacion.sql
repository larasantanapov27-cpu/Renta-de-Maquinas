-- Ejecutar solo en una base aislada renta_pruebas_... creada por tests/mysql.php.
-- Estas consultas son de lectura; no cambian información real.
-- En cada caso ejecutarlas antes y después de enviar el formulario.
SELECT DATABASE() AS base_actual;

-- Motores: todas las tablas involucradas deben mostrar InnoDB.
SELECT TABLE_NAME, ENGINE FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME IN ('rentas','detalle_rentas','maquinas','mantenimientos',
'clientes','detalle_tarifa','estados_renta','estados_maquina',
'tipos_mantenimiento','bajas_maquinas');

-- 1. Registrar renta: cabecera + detalles + estado Rentada.
-- Localizar la renta por la máquina de prueba, sin asumir su AUTO_INCREMENT.
SELECT r.*, d.* FROM rentas r
JOIN detalle_rentas d ON d.id_renta=r.id_renta
WHERE d.id_maquina=9001 ORDER BY r.id_renta,d.id_detalle_renta;
SELECT m.id_maquina,m.codigo,e.nombre AS estado
FROM maquinas m JOIN estados_maquina e USING(id_estado_maquina)
WHERE m.id_maquina=9001;

-- 2. Devolver maquinaria: fecha real + estado de renta + disponibilidad.
SELECT r.id_renta,e.nombre AS estado_renta,d.id_detalle_renta,
d.id_maquina,d.fecha_devolucion_real
FROM rentas r JOIN estados_renta e USING(id_estado_renta)
JOIN detalle_rentas d USING(id_renta)
WHERE d.id_maquina=9001 ORDER BY r.id_renta,d.id_detalle_renta;
SELECT m.id_maquina,e.nombre AS estado_maquina
FROM maquinas m JOIN estados_maquina e USING(id_estado_maquina)
WHERE m.id_maquina=9001;

-- 3. Registrar/cerrar mantenimiento: registro + estado Mantenimiento/Disponible.
SELECT * FROM mantenimientos WHERE id_maquina=9001 ORDER BY id_mantenimiento;
SELECT m.id_maquina,e.nombre AS estado_maquina
FROM maquinas m JOIN estados_maquina e USING(id_estado_maquina)
WHERE m.id_maquina=9001;

-- Capturas completas: son las consultas usadas por las pruebas para comparar
-- igualdad exacta de todas las filas antes y después de cada ROLLBACK.
SELECT * FROM rentas ORDER BY id_renta;
SELECT * FROM detalle_rentas ORDER BY id_detalle_renta;
SELECT * FROM maquinas ORDER BY id_maquina;
SELECT * FROM mantenimientos ORDER BY id_mantenimiento;
