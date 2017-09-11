<?php

$comunes = new Comunes();

$tabla = $name_form = "libro_ventas";
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
    }

    switch ($tipob) {
        case "exacta":
            $instruccion = $comunes->buscar_exacta($tabla, $des, $busqueda,"",$columnas);
            break;
        case "todas":
            $instruccion = $comunes->buscar_todas($tabla, $des, $busqueda,"",$columnas);
            break;
        case "cualquiera":
            $instruccion = $comunes->buscar_cualquiera($tabla, $des, $busqueda,"",$columnas);
            break;
    }
}else {
    $instruccion = "SELECT a.id as id_libro, caja_host, fecha, usuario  FROM $tabla a inner join caja_impresora b on a.serial_impresora=b.serial_impresora inner join usuarios c on a.id_usuario_creacion=c.cod_usuario order by id_libro desc";
}
//mysql_set_charset('utf8');
$num_paginas = $comunes->obtener_num_paginas($instruccion);
$pagina = $comunes->obtener_pagina_actual($pagina, $num_paginas);
$campos = $comunes->paginacion($pagina, $instruccion);
$smarty->assign("registros", $campos);

$parametros = $menu->ObtenerFilasBySqlSelect("SELECT moneda FROM parametros_generales;");
#$smarty->assign("cabecera", array("<td style='width:10%'>C&oacute;d. Item</td>", "<td style='width:15%'>C&oacute;d. Barras</td>", "<td style='width:35%'>Descripci&oacute;n</td>", "<td style='width:10%'>Precio</td>", "<td style='width:10%'>Total Inventario</td>", "<td style='width:10%'>Existencia M&iacute;n.</td>", "<td style='width:10%'>Existencia M&aacute;x.</td>"));
$smarty->assign("cabecera", array("Nro de Libro Ventas", "Caja", "Fecha ", "Usuario"));
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
$smarty->assign("option_values", array("id_libro", "caja_host"));
$smarty->assign("option_output", array("Nro de Libro de Ventas ", "Usuario"));
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
