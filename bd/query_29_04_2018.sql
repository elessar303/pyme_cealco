ALTER TABLE `tickets_entrada_salida` ADD `placa` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `tipo_ticket`;
INSERT INTO `modulos`(`cod_modulo_padre`, `nom_menu`, `archivo_php`, `archivo_tpl`, `orden`, `img_ruta`, `visible`) VALUES ('3', 'Entrada T&uacute;nel Almac&eacute;n', 'tunel_almacen.php', 'tunel_almacen.tpl', '14', '../../libs/imagenes/traslados.png', '1');
INSERT INTO `tipo_movimiento_almacen`(`descripcion`, `operacion`, `movimiento_servicio`) VALUES ('Cargo Tunel', '+', '9'), ('Descargo Tunel', '-', '10');
INSERT INTO `subseccion` (`opt_subseccion`, `archivo_tpl`, `archivo_php`, `cod_seccion`, `descripcion`) VALUES ('entradatunelpaleta', 'entradatunelpaleta_nuevo.tpl', 'entradatunelpaleta_nuevo.php', '279', 'Nuevo Cargo Tunel');
INSERT INTO `modulos`(`cod_modulo_padre`, `nom_menu`, `archivo_php`, `archivo_tpl`, `orden`, `img_ruta`, `visible`) VALUES ('3', 'T&uacute;nel Salida Almac&eacute;n','tunel_salida_almacen.php', 'tunel_salida_almacen.tpl','15','../../libs/imagenes/traslados.png', '1'
);
INSERT INTO `subseccion` (`opt_subseccion`, `archivo_tpl`, `archivo_php`, `cod_seccion`, `descripcion`) VALUES ('add', 'tunel_salida_almacen_nuevo.tpl', 'tunel_salida_almacen_nuevo.php', '280', 'Agregar Tunel Salida Nuevo');
ALTER TABLE `calidad_almacen_detalle` ADD `id_presentacion` INT NOT NULL AFTER `id_marca`;
ALTER TABLE `kardex_almacen_detalle` ADD `id_presentacion` INT NOT NULL AFTER `id_marca`;