ALTER TABLE  `tipo_movimiento_almacen` ADD  `movimiento_servicio` INT NULL DEFAULT  '0' AFTER  `operacion` ;
INSERT INTO `pyme_prueba_standar_ceal`.`modulos` (`cod_modulo`, `cod_modulo_padre`, `nom_menu`, `archivo_php`, `archivo_tpl`, `orden`, `img_ruta`, `visible`) VALUES (NULL, '3', 'Movimientos Servicios', 'movimientos_servicios.php', 'movimientos_servicios.tpl', '5', '../../libs/imagenes/11.png', '1');

INSERT INTO `pyme_prueba_standar_ceal`.`subseccion` (`opt_subseccion`, `archivo_tpl`, `archivo_php`, `cod_seccion`, `descripcion`) VALUES ('movimientoservicio', 'movimientos_servicios_lista.tpl', 'movimientos_servicios_lista.php', '266', 'Lista de los servicios del movimiento');



CREATE TABLE IF NOT EXISTS `movimiento_almacen_servicio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_movimiento_almacen` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT '1',
  `fechacreacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_creacion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_movimiento_almacen` (`id_movimiento_almacen`,`id_servicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `pyme_prueba_standar_ceal`.`subseccion` (`opt_subseccion`, `archivo_tpl`, `archivo_php`, `cod_seccion`, `descripcion`) VALUES ('movimientoservicioadd', 'movimientos_servicios_add.tpl', 'movimientos_servicios_add.php', '266', 'Agregar un Servicio al movimiento');





