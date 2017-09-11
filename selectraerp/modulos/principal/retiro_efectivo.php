<?php

$comunes = new Comunes();
require_once('../../../generalp.config.inc.php');
$pos=POS;
$tabla = $name_form = "retiro_efectivo";
$tipob = @$_GET['tipo'];
$des = @$_GET['des'];
$pagina = @$_GET['pagina'];
$busqueda = @$_GET['busqueda'];
$string_cod_item_forma = "and cod_item_forma = 1"; //Productos

if (isset($_POST['buscar']) || $tipob != NULL) {
    if (!$tipob) {
        $tipob = $_POST['palabra'];
        $des = $_POST['buscar'];       
        $busqueda =$_POST['busqueda'];
        $columnas="*";
    };
    $join= "as t left join $pos.people as pos on t.id_cajero=pos.id left join usuarios u on t.id_cajero=u.cod_usuario";

    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta_join($tabla, $des, $busqueda, $join);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas_join($tabla, $des, $busqueda,$join);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera_join($tabla, $des, $busqueda, $join);
            break;
            $instruccion.="order by t.id";
    }
}else {
     $instruccion = "SELECT * FROM $tabla as t left join $pos.people as pos on t.id_cajero=pos.id left join usuarios u on  t.id_cajero=u.cod_usuario order by t.id";
}
//mysql_set_charset('utf8');
$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);
$smarty->assign("registros", $campos);

$parametros = $menu->ObtenerFilasBySqlSelect("SELECT moneda FROM parametros_generales;");
#$smarty->assign("cabecera", array("<td style='width:10%'>C&oacute;d. Item</td>", "<td style='width:15%'>C&oacute;d. Barras</td>", "<td style='width:35%'>Descripci&oacute;n</td>", "<td style='width:10%'>Precio</td>", "<td style='width:10%'>Total Inventario</td>", "<td style='width:10%'>Existencia M&iacute;n.</td>", "<td style='width:10%'>Existencia M&aacute;x.</td>"));
$smarty->assign("cabecera", array("Cajero", "Fecha", "Usuario", "Total"));
$smarty->assign("limitePaginacion", $comunes->LimitePaginaciones);
$smarty->assign("num_paginas", $num_paginas);
$smarty->assign("pagina", $pagina);

$smarty->assign("busqueda", $busqueda);
$smarty->assign("des", $des);
$smarty->assign("tipo", $tipob);
$smarty->assign("cantidadFilas", $comunes->getFilas());

$campos = $menu->ObtenerFilasBySqlSelect("SELECT * FROM modulos WHERE cod_modulo = {$_GET["opt_seccion"]};");
$smarty->assign("campo_seccion", $campos);

//**************************************************************************
//Criterios de Busqueda ****************************************************
//**************************************************************************
$smarty->assign("option_values", array("id_cajero", "cod_usuario"));
$smarty->assign("option_output", array("Cajero", "Usuario"));
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
?>
