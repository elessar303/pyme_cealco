<?php

class Comunes extends ConexionComun {

    var $LimitePaginaciones = 20;

    function __construc() {
        parent::__construct();
    }

    function buscar_exacta_producto($valor, $campo) {
        $consulta = "SELECT *, (
SELECT ifnull( sum( cantidad ) , 0 )
FROM item_existencia_almacen
WHERE id_item = i.id_item) AS total_inventario item AS i, marca AS mc, unidad_medida AS um, grupo as grup where i.cod_item_forma = 1 and " . $campo . " LIKE '%" . $valor . "%' ";
        return $consulta;
    }

    function buscar_todas_producto($modulo, $temp, $campo, $campo2 = "", $columnas = "*") {

        $cadenas = explode(" ", $temp);

        $sql2 = "select " . $columnas . ", (
SELECT ifnull( sum( cantidad ) , 0 ) from item_existencia_almacen WHERE id_item = i.id_item) AS total_inventario FROM item AS i, marca AS mc, unidad_medida AS um, grupo as grup WHERE i.cod_item_forma = 1 AND";

        foreach ($cadenas as $actual) {
            $sql2 = $sql2 . " " . $campo . " LIKE '%" . $actual . "%' AND";
        }
        $longitud = strlen($sql2);
        $sql2 = substr($sql2, 0, $longitud - 4);
        return $sql2 . " " . $campo2;
    }

    function buscar_cualquiera_producto($temp, $campo) {
        $cadenas = explode(" ", $temp);
        $sql2 = "select *, (
SELECT ifnull( sum( cantidad ) , 0 ) from item_existencia_almacen WHERE id_item = i.id_item) AS total_inventario FROM item AS i, marca AS mc, unidad_medida AS um, grupo as grup WHERE i.cod_item_forma = 1 AND (";
        foreach ($cadenas as $actual) {
            $sql2 = $sql2 . " " . $campo . " LIKE '%" . $actual . "%' OR";
        }
        $longitud = strlen($sql2);
        $sql2 = substr($sql2, 0, $longitud - 3);
        $sql2 = $sql2 . ")";
        return $sql2;
    }

    function buscar_exacta($modulo, $valor, $campo, $campo2 = "", $columnas = "*") {
        $consulta = "select " . $columnas . " from " . $modulo . " where " . $campo . " LIKE '%" . $valor . "%' " . $campo2;
        return $consulta;
    }

    function buscar_exacta_join($modulo, $valor, $campo, $join, $orden) {
        $consulta = "select * from " . $modulo . " " . $join . " and " . $campo . " LIKE '%" . $valor . "%' " . $orden;
        return $consulta;
    }

    function buscar_todas($modulo, $temp, $campo, $campo2 = "", $columnas = "*") {

        $cadenas = explode(" ", $temp);

        $sql2 = "select " . $columnas . " from " . $modulo . " where";

        foreach ($cadenas as $actual) {
            $sql2 = $sql2 . " " . $campo . " LIKE '%" . $actual . "%' AND";
        }
        $longitud = strlen($sql2);
        $sql2 = substr($sql2, 0, $longitud - 4);
        return $sql2 . " " . $campo2;
    }

    function buscar_todas_join($modulo, $temp, $campo, $join) {

        $cadenas = explode(" ", $temp);

        $sql2 = "select * from " . $modulo . " " . $join . " where";

        foreach ($cadenas as $actual) {
            $sql2 = $sql2 . " " . $campo . " LIKE '%" . $actual . "%' AND";
        }
        $longitud = strlen($sql2);
        $sql2 = substr($sql2, 0, $longitud - 4);
        return $sql2 . " " . $campo2;
    }

    function buscar_cualquiera($modulo, $temp, $campo, $campo2 = "", $columnas = "*") {
        $cadenas = explode(" ", $temp);
        $sql2 = "select " . $columnas . " from " . $modulo . " where (";
        foreach ($cadenas as $actual) {
            $sql2 = $sql2 . " " . $campo . " LIKE '%" . $actual . "%' OR";
        }
        $longitud = strlen($sql2);
        $sql2 = substr($sql2, 0, $longitud - 3);
        $sql2 = $sql2 . ")";
        return $sql2 . " " . $campo2;
    }

    function buscar_cualquiera_join($modulo, $temp, $campo, $join) {
        $cadenas = explode(" ", $temp);
        $sql2 = "select * from " . $modulo . " " . $join . " where (";
        foreach ($cadenas as $actual) {
            $sql2 = $sql2 . " " . $campo . " LIKE '%" . $actual . "%' OR";
        }
        $longitud = strlen($sql2);
        $sql2 = substr($sql2, 0, $longitud - 3);
        $sql2 = $sql2 . ")";
        return $sql2 . " " . $campo2;
    }

    function Notificacion() {
        return "<b><img cursor=\"absmiddle\" src=\"../../libs/imagenes/ico_note_1.gif\"> No se encontraron filas en la busqueda.</b>";
    }

    function obtener_num_paginas($consulta) {


        $this->rcampos = $this->ObtenerFilasBySqlSelect($consulta);

        if ($this->rcampos != "") {
            $numero_filas = $this->getFilas();
            $num_paginas = ceil($numero_filas / $this->LimitePaginaciones);
            return $num_paginas;
        } else {
            return 0;
        }
    }

    function obtener_pagina_actual($pagina, $num_paginas) {
        if ($pagina < 1) {
            $pagina = 1;
        } else {
            if ($pagina > $num_paginas && $num_paginas != 0) {
                $pagina = $num_paginas;
            }
        }
        return $pagina;
    }

    function paginacion($pagina, $consulta) {

        $inicio = ($pagina * $this->LimitePaginaciones) - $this->LimitePaginaciones;
        $consulta2 = $consulta . " limit " . $inicio . ", " . $this->LimitePaginaciones . "";
        //echo $consulta2;
        //return $consulta2;
        $this->rcampos = $this->ObtenerFilasBySqlSelect($consulta2);


        return $this->rCampos;
    }

	function codigo_pos($par) 
	{

        	$md5=md5($par);
			$codigo="";
			$codigo=substr($md5,0,8).'-'.substr($md5,8,4).'-'.substr($md5,12,4).'-'.substr($md5,16,4).'-'.substr($md5,20,12);
        	return $codigo;
    }

}

?>