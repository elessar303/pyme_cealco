<?php

$comunes = new Comunes();

$tabla = "movimientos_bancarios";
$tabla_tipomov = "tipo_movimientos_ban";
$name_form = "movimientos_bancarios";
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];

if (isset($_GET["cod_cuenta"])) {
    $sql="SELECT tb.cod_tesor_bandodet, tb.cod_banco, tb.nro_cuenta,
                    b.descripcion AS descripcion_banco, tb.descripcion AS descripcion_cuenta
            FROM tesor_bancodet tb
            INNER JOIN banco b ON b.cod_banco = tb.cod_banco
            WHERE tb.cod_tesor_bandodet = " . $_GET["cod_cuenta"];
    $campos = $comunes->ObtenerFilasBySqlSelect($sql);
    $smarty->assign("datos_banco", $campos);
}

if (isset($_POST['buscar']) || $tipob != NULL) {
    if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar'];
        $busqueda = $_POST['busqueda'];
    }

    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta($tabla, $des, $busqueda);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas($tabla, $des, $busqueda);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera($tabla, $des, $busqueda);
            break;
    }
    $instruccion .= " and cod_tesor_bandodet = " . $_GET["cod_cuenta"];
} else {
    $instruccion = "SELECT * FROM  " . $tabla . " LEFT JOIN " . $tabla_tipomov . " ON cod_tipo_movimientos_ban=tipo_movimiento WHERE cod_tesor_bancodet = " . @$_GET["cod_cuenta"];
}

$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);

$smarty->assign("registros", $campos);
$smarty->assign("cabecera", array("C&oacute;digo", "Tipo", "N&uacute;mero", "Fecha", "Monto", "Concepto"));
$smarty->assign("limitePaginacion", $comunes->LimitePaginaciones);
$smarty->assign("num_paginas", $num_paginas);
$smarty->assign("pagina", $pagina);

$smarty->assign("busqueda", $busqueda);
$smarty->assign("des", $des);
$smarty->assign("tipo", $tipob);
$smarty->assign("cantidadFilas", $comunes->getFilas());

$campos = $menu->ObtenerFilasBySqlSelect("select * from modulos where cod_modulo= " . $_GET["opt_seccion"]);
$smarty->assign("campo_seccion", $campos);

//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("cod_movimiento_ban", "tipo_movimiento", "numero_movimiento"));
$smarty->assign("option_output", array("Cod. Movimiento", "Tipo Mov.", "Numero Mov."));
$smarty->assign("option_selected", $busqueda);
//**************************************************************************
//**************************************************************************
//**************************************************************************
//**************************************************************************
//Nombre del Formulario****************************************************
//**************************************************************************
$smarty->assign("name_form", $name_form);
//**************************************************************************
//**************************************************************************
$smarty->assign("mensaje", $comunes->Notificacion());
/**
 * veficamos si tiene se generaron los cheques. se haberce generado
 * no devuelve si para indicar en el tpl la opcion de !generar cheques.
 *
 */
$sqlcheques_generados = "
select if(che.cod_chequera,'si','no') as cheques_generados,  vw.cod_chequera   from vista_chequera_lista vw left join cheque che
 on che.cod_chequera = vw.cod_chequera
where  vw.cod_chequera
group by vw.cod_chequera";
$cheques_generados = $comunes->ObtenerFilasBySqlSelect($sqlcheques_generados);
foreach ($cheques_generados as $i => $valor) {
    $validarChequesGenerados[$valor["cod_chequera"]] = $valor["cheques_generados"];
}

$smarty->assign("validarChequesGenerados", $validarChequesGenerados);
?>
