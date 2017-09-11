<?php

include("../../libs/php/clases/producto.php");
include("../../libs/php/clases/correlativos.php");
include("../../../menu_sistemas/lib/common.php");

$productos = new Producto();
$correlativos = new Correlativos();
$comun = new Comunes();
$campos_almacen = $productos->ObtenerFilasBySqlSelect("SELECT * FROM almacen");
$smarty->assign("campos_almacen", $campos_almacen);
$almacenes2 = $productos->ObtenerFilasBySqlSelect("
    SELECT descripcion, '0' as cantidad
    FROM almacen
    LEFT JOIN item_existencia_almacen ON (id_item = almacen.cod_almacen)");

if (isset($_POST["aceptar"]))
{
    $productos->BeginTrans();
    $nro_producto = $correlativos->getUltimoCorrelativo("cod_producto", 1, "si", "P");# Originalmente $nro_producto era el valor guardado en BD para el campo `item`.`cod_item`
    $_POST["iva"] = $_POST["monto_exento"] == 0 ? $_POST["iva"] : 0;
    

	
	$mensajefoto="";
	if($_FILES["foto"]["name"]!="")
	{
		$allowedExts = array("jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["foto"]["name"]);
		$extension = end($temp);
		if ((($_FILES["foto"]["type"] == "image/gif")
		|| ($_FILES["foto"]["type"] == "image/jpeg")
		|| ($_FILES["foto"]["type"] == "image/jpg")
		|| ($_FILES["foto"]["type"] == "image/pjpeg")
		|| ($_FILES["foto"]["type"] == "image/x-png")
		|| ($_FILES["foto"]["type"] == "image/png"))
		&& ($_FILES["foto"]["size"] < 2000000)
		&& in_array($extension, $allowedExts))
		{
			if ($_FILES["foto"]["error"] > 0)
		   {
		   	$mensajefoto="Error Numero: " . $_FILES["foto"]["error"] . "<br>";
		   }
		  	else
		   {
			   move_uploaded_file($_FILES["foto"]["tmp_name"],"../../imagenes/fotos/" . $_FILES["foto"]["name"]);
		      $foto="fotos/" . $_FILES["foto"]["name"];
		    }
		}
		else
		{
			$mensajefoto="Imagen invalida";
		}
	}

	if($_FILES["foto1"]["name"]!="")
	{
		$allowedExts = array("jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["foto1"]["name"]);
		$extension = end($temp);
		if ((($_FILES["foto1"]["type"] == "image/gif")
		|| ($_FILES["foto1"]["type"] == "image/jpeg")
		|| ($_FILES["foto1"]["type"] == "image/jpg")
		|| ($_FILES["foto1"]["type"] == "image/pjpeg")
		|| ($_FILES["foto1"]["type"] == "image/x-png")
		|| ($_FILES["foto1"]["type"] == "image/png"))
		&& ($_FILES["foto1"]["size"] < 2000000)
		&& in_array($extension, $allowedExts))
		{
			if ($_FILES["foto1"]["error"] > 0)
		   {
		   	$mensajefoto="Error Numero: " . $_FILES["foto1"]["error"] . "<br>";
		   }
		  	else
		   {
			   move_uploaded_file($_FILES["foto1"]["tmp_name"],"../../imagenes/fotos/" . $_FILES["foto1"]["name"]);
		      $foto1="fotos/" . $_FILES["foto1"]["name"];
		    }
		}
		else
		{
			$mensajefoto="Imagen 1 invalida";
		}
	}

	if($_FILES["foto2"]["name"]!="")
	{
		$allowedExts = array("jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["foto2"]["name"]);
		$extension = end($temp);
		if ((($_FILES["foto2"]["type"] == "image/gif")
		|| ($_FILES["foto2"]["type"] == "image/jpeg")
		|| ($_FILES["foto2"]["type"] == "image/jpg")
		|| ($_FILES["foto2"]["type"] == "image/pjpeg")
		|| ($_FILES["foto2"]["type"] == "image/x-png")
		|| ($_FILES["foto2"]["type"] == "image/png"))
		&& ($_FILES["foto2"]["size"] < 2000000)
		&& in_array($extension, $allowedExts))
		{
			if ($_FILES["foto2"]["error"] > 0)
		   {
		   	$mensajefoto="Error Numero: " . $_FILES["foto2"]["error"] . "<br>";
		   }
		  	else
		   {
			   move_uploaded_file($_FILES["foto2"]["tmp_name"],"../../imagenes/fotos/" . $_FILES["foto2"]["name"]);
		      $foto2="fotos/" . $_FILES["foto2"]["name"];
		    }
		}
		else
		{
			$mensajefoto="Imagen invalida";
		}
	}
    
   if($_FILES["foto3"]["name"]!="")
	{
		$allowedExts = array("jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["foto3"]["name"]);
		$extension = end($temp);
		if ((($_FILES["foto3"]["type"] == "image/gif")
		|| ($_FILES["foto3"]["type"] == "image/jpeg")
		|| ($_FILES["foto3"]["type"] == "image/jpg")
		|| ($_FILES["foto3"]["type"] == "image/pjpeg")
		|| ($_FILES["foto3"]["type"] == "image/x-png")
		|| ($_FILES["foto3"]["type"] == "image/png"))
		&& ($_FILES["foto3"]["size"] < 2000000)
		&& in_array($extension, $allowedExts))
		{
			if ($_FILES["foto3"]["error"] > 0)
		   {
		   	$mensajefoto="Error Numero: " . $_FILES["foto3"]["error"] . "<br>";
		   }
		  	else
		   {
			   move_uploaded_file($_FILES["foto3"]["tmp_name"],"../../imagenes/fotos/" . $_FILES["foto3"]["name"]);
		      $foto3="fotos/" . $_FILES["foto3"]["name"];
		    }
		}
		else
		{
			$mensajefoto="Imagen invalida";
		}
	}
    
	if($_FILES["foto4"]["name"]!="")
	{
		$allowedExts = array("jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["foto4"]["name"]);
		$extension = end($temp);
		if ((($_FILES["foto4"]["type"] == "image/gif")
		|| ($_FILES["foto4"]["type"] == "image/jpeg")
		|| ($_FILES["foto4"]["type"] == "image/jpg")
		|| ($_FILES["foto4"]["type"] == "image/pjpeg")
		|| ($_FILES["foto4"]["type"] == "image/x-png")
		|| ($_FILES["foto4"]["type"] == "image/png"))
		&& ($_FILES["foto4"]["size"] < 2000000)
		&& in_array($extension, $allowedExts))
		{
			if ($_FILES["foto4"]["error"] > 0)
		   {
		   	$mensajefoto="Error Numero: " . $_FILES["foto4"]["error"] . "<br>";
		   }
		  	else
		   {
			   move_uploaded_file($_FILES["foto4"]["tmp_name"],"../../imagenes/fotos/" . $_FILES["foto4"]["name"]);
		      $foto4="fotos/" . $_FILES["foto4"]["name"];
		    }
		}
		else
		{
			$mensajefoto="Imagen invalida";
		}
	}
    $idpos=$comun->codigo_pos($_POST["descripcion1"]);
    $_POST["referencia"]=$_POST["cod_item"];
    $instruccion = "
        INSERT INTO `item`(
        `cod_item`, `codigo_barras`, `codigo_cpe`, `costo_actual`, `descripcion1`, `descripcion2`, `descripcion3`, `referencia`,
        `codigo_fabricante`, `precio1`, `utilidad1`, `coniva1`, `precio2`, `utilidad2`,
        `coniva2`, `precio3`, `utilidad3`, `coniva3`, `existencia_min`,
        `existencia_max`, `monto_exento`, `iva`,
        `cod_departamento`, `cod_grupo`,sub_categoria, `id_marca`, `cod_linea`,
        `estatus`,`usuario_creacion`, `fecha_creacion`, `cod_item_forma`,unidad_empaque, cantidad, seriales,garantia, tipo_item, tipo_prod,
        costo_promedio, costo_anterior, cuenta_contable1, cuenta_contable2, serial1,
        foto,foto1,foto2,foto3,foto4,cantidad_bulto,kilos_bulto,proveedor,fecha_ingreso,origen,costo_cif,costo_origen,temporada,
        mate_compo_clase, punto_pedido,tejido, reg_sanit, cod_barra_bulto, observacion, cont_licen_nro, precio_cont, aprob_arte, propiedad,
        regulado,cestack_basica,bcv,unidadxpeso, unidad_venta, pesoxunidad, itempos, producto_vencimiento, tipo_almacenamiento, sae)
        VALUES(
        '{$_POST["cod_item"]}', '{$_POST["cod_barras"]}','{$_POST["cod_cpe"]}', '{$_POST["costo_actual"]}', '{$_POST["descripcion1"]}',
        '{$_POST["descripcion2"]}', '" . $_POST["descripcion3"] . "', '" . $_POST["referencia"] . "', '" . $_POST["codigo_fabricante"] . "',
        '" . $_POST["precio_1"] . "', '" . $_POST["utilidad1"] . "', '" . $_POST["coniva1"] . "', '" . $_POST["precio_2"] . "',
        '" . $_POST["utilidad2"] . "', '" . $_POST["coniva2"] . "', '" . $_POST["precio_3"] . "', '" . $_POST["utilidad3"] . "',
        '" . $_POST["coniva3"] . "', '" . $_POST["existencia_min"] . "',  '" . $_POST["existencia_max"] . "', '" . $_POST["monto_exento"] . "',
        '" . $_POST["iva"] . "', '" . $_POST["cod_departamento"] . "', '" . $_POST["cod_grupo"] . "'," . $_POST["sub_categoria"] . ",  '" . $_POST["marca"] . "', '" . $_POST["cod_linea"] . "',
        '" . $_POST["estatus"] . "', '" . $login->getUsuario() . "', CURRENT_TIMESTAMP, 1, '" . $_POST["empaque"] . "',
        '" . $_POST["unidad_empaque"] . "', '" . $_POST["serial"] . "', '" . $_POST["garantia"] . "', '" . $_POST["tipo_producto"] . "', '" . $_POST["tipo"] . "',
        '" . $_POST["costo_promedio"]."', '" . $_POST["costo_anterior"] . "', '" . $_POST["cuenta_contable1"] . "', '" . $_POST["cuenta_contable2"] . "', '" . $_POST["serial1"] . "','$foto','$foto1','$foto2','$foto3','$foto4', '".$_POST["cantidad_bulto"]."', '".$_POST["kilos_bulto"]."', '".$_POST["proveedor"]."', '".fecha_sql($_POST["fecha_ingreso"])."', '".$_POST["origen"]."', '".$_POST["costo_cif"]."', '".$_POST["costo_origen"]."', '".$_POST["temporada"]."', '".$_POST["mate_compo_clase"]."', '".$_POST["punto_pedido"]."', '".$_POST["tejido"]."', '".$_POST["reg_sanit"]."', '".$_POST["cod_barra_bulto"]."', '".$_POST["observacion"]."', '".$_POST["cont_licen_nro"]."', '".$_POST["precio_cont"]."', '".$_POST["aprob_arte"]."', '".$_POST["propiedad"]."','".$_POST["regulado"]."','".$_POST["cesta"]."','".$_POST["bcv"]."','".$_POST["unidadxpeso"]."',
        '" . $_POST["unidad_venta"]."', '" . $_POST["pesoxunidad"]."','".$idpos."','".$producto_vencimiento."', '".$_POST["tipo_almacenamiento"]."', '".$_POST["sae"]."');";
    $productos->ExecuteTrans($instruccion);
    if ($productos->errorTransaccion == 1) {
        Msg::setMessage("<span style=\"color:#62875f;\">Producto Generado Exitosamente con en Nro. " . $nro_producto . "</span>");
    }
    if ($productos->errorTransaccion == 0) {
        Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear el producto.</span>");
    }
	if ($mensajefoto!="") {
        Msg::setMessage($mensajefoto);
    }
    $nro_producto = $correlativos->getUltimoCorrelativo("cod_producto", 1, "no", "");
    $productos->ExecuteTrans("UPDATE correlativos SET contador = '{$nro_producto}' WHERE campo = 'cod_producto'");
    $productos->CommitTrans($productos->errorTransaccion);

    $i = 1;
    $codigo = $_POST['id' . $i];
    while ($codigo != '') {
        if ($_POST['id' . $i] != '') {
            $query = "INSERT INTO productos_kit VALUES (LAST_INSERT_ID(),'" . $_POST['item' . $i] . "','" . $_POST['cantidad' . $i] . "');";
            $productos->ExecuteTrans($query);
        }
        $codigo = $_POST['id' . $i];
        $i++;
    }

    //para obtener los valores de los precios EN REVISION
    	$producto = $productos->ObtenerFilasBySqlSelect("SELECT * FROM item where cod_item='".$_POST["cod_item"]."'");
    	$region=$_POST["region"];
    	$precio1=$_POST["precio1"];
    	$precio2=$_POST["precio2"];
    	$precio3=$_POST["precio3"];    	
    	$i=0;
    	
    	
    	foreach ($region as $key ) {    		
    		// ingresa la colimna del precio red comercial
    		if($precio1[$i]!=""){
	    		$instruccion = "INSERT INTO `item_precio` (
				`id_region`,
				`id_producto`,
				`tipo_precio`,
				`precio`
				)
				VALUES (
				 '".$key."', '".$producto[0]["id_item"]."','1' ,'".$precio1[$i]."')";

	            $productos->Execute2($instruccion);  
       		 }

            // ingresa la colimna del precio PAE
       		 if($precio2[$i]!=""){
	            $instruccion2 = "INSERT INTO `item_precio` (
				`id_region`,
				`id_producto`,
				`tipo_precio`,
				`precio`
				)
				VALUES (
				 '".$key."', '".$producto[0]["id_item"]."','2' ,'".$precio2[$i]."')";

	            $productos->Execute2($instruccion2); 
            } 
             // ingresa la colimna del precio Pedevalito   
            if($precio3[$i]!=""){
	            $instruccion3 = "INSERT INTO `item_precio` (
				`id_region`,
				`id_producto`,
				`tipo_precio`,
				`precio`
				)
				VALUES (
				 '".$key."', '".$producto[0]["id_item"]."','3' ,'".$precio3[$i]."')";

	            $productos->Execute2($instruccion3);	
	      	}
            $i++;
  	    }

    // +++++++++++++

    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
   
}

$lista_impuestos = $productos->ObtenerFilasBySqlSelect("SELECT descripcion FROM tipo_impuesto ORDER BY descripcion;");
/*$arraySelectOutPut = "";
foreach ($lista_impuestos as $key_impuestos => $item) {
    //$arraySelectOption[] = $item["cod_tipo_impuesto"];
    $arraySelectOutPut[] = $item["descripcion"];
}
$smarty->assign("key_impuestos", $key_impuestos);*/
$smarty->assign("lista_impuestos", $lista_impuestos);
/*$smarty->assign("option_values_impuestos", $arraySelectOption);
$smarty->assign("option_output_impuestos", $arraySelectOutPut);*/

$smarty->assign("nro_productoOLD", $correlativos->getUltimoCorrelativo("cod_producto", 0, "si", "P"));
$smarty->assign("nro_productoNEW", $correlativos->getUltimoCorrelativo("cod_producto", 1, "si", "P"));

$ultimocodigo = $productos->ObtenerFilasBySqlSelect("SELECT cod_item FROM item WHERE cod_item_forma = 1 ORDER BY id_item DESC LIMIT 0,1");
$smarty->assign("ultimo_codigo", $ultimocodigo);

$arraySelectOption = "";
$arraySelectoutPut = "";
// Cargando departamentos en combo select
$campos_comunes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM departamentos");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectOutPut[] = $item["cod_departamento"];
}
$smarty->assign("option_values_departamentos", $arraySelectOption);
$smarty->assign("option_output_departamentos", $arraySelectOutPut);

// Cargando grupo en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM grupo");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_grupo"];
}
$smarty->assign("option_values_grupo", $arraySelectOption);
$smarty->assign("option_output_grupo", $arraySelectoutPut);

//cargando select de unidad de venta
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes= $productos->ObtenerFilasBySqlSelect("SELECT * FROM unidad_venta order by id");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id"];
    $arraySelectoutPut[] = utf8_encode($item["unidad_venta"]);
}
$smarty->assign("option_values_unidad_venta", $arraySelectOption);
$smarty->assign("option_output_unidad_venta", $arraySelectoutPut);

//cargando select de marca
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes= $productos->ObtenerFilasBySqlSelect("SELECT * FROM marca order by marca");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id"];
    $arraySelectoutPut[] = utf8_encode($item["marca"]);
}
$smarty->assign("option_values_marca", $arraySelectOption);
$smarty->assign("option_output_marca", $arraySelectoutPut);
//fin de carga de marca


//cargando select de unidad de peso
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes= $productos->ObtenerFilasBySqlSelect("SELECT * FROM unidad_medida order by id");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id"];
    $arraySelectoutPut[] = utf8_encode($item["nombre_unidad"]);
}
$smarty->assign("option_values_unidadxpeso", $arraySelectOption);
$smarty->assign("option_output_unidadxpeso", $arraySelectoutPut);
//fin de carga de unidad de peso


//cargando select de unidad de Empaque
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes= $productos->ObtenerFilasBySqlSelect("SELECT * FROM unidad_empaque order by id");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id"];
    $arraySelectoutPut[] = utf8_encode($item["nombre_unidad"]);
}
$smarty->assign("option_values_empaque", $arraySelectOption);
$smarty->assign("option_output_empaque", $arraySelectoutPut);
//fin de carga de marca


// Cargando Linea en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM linea");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption = $item["descripcion"];
    $arraySelectoutPut = $item["cod_linea"];
}
$smarty->assign("option_values_linea", $arraySelectOption);
$smarty->assign("option_output_linea", $arraySelectoutPut);

//Cargar % I.V.A de la tabla de parametros generales.
$parametros_generales = $productos->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
$smarty->assign("parametros_generales", $parametros_generales);

//Cargar Almacenes
$almacenes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM almacen");
$smarty->assign("almacenes", $almacenes);

$smarty->assign("almacenes2", $almacenes2);
//$smarty->assign("prueba",$almacenes2[0]['descripcion']);
// CONSULTA DE CUENTAS CONTABLES
$global = new bd(SELECTRA_CONF_PYME);
$sentencia = "SELECT * FROM nomempresa WHERE bd = '{$_SESSION['EmpresaFacturacion']}';";
$contabilidad = $global->query($sentencia);
$fila = $contabilidad->fetch_assoc();

$valueSELECT = "";
$outputSELECT = "";
$contabilidad = $productos->ObtenerFilasBySqlSelect("SELECT * FROM {$fila['bd_contabilidad']}.cwconcue WHERE Tipo='P';");
$fila=$productos->getFilas();
if($fila!=0){
	foreach ($contabilidad as $key => $cuenta) {
	    $valueSELECT[] = $cuenta["Cuenta"];
	    $outputSELECT[] = $cuenta["Cuenta"] . " - " . $cuenta["Descrip"];
	}
	$smarty->assign("option_values_cuenta", $valueSELECT);
	$smarty->assign("option_output_cuenta", $outputSELECT);
}
$valueSELECT = "";
$outputSELECT = "";
$parametros_generales = $productos->ObtenerFilasBySqlSelect("SELECT porcentaje_impuesto_principal, iva_a, iva_b, iva_c, (SELECT iva FROM item WHERE id_item = '{$_GET["cod"]}') AS iva FROM parametros_generales;");
$parametros_generales_array = array($parametros_generales[0]['porcentaje_impuesto_principal'], $parametros_generales[0]['iva_a'], $parametros_generales[0]['iva_b'], $parametros_generales[0]['iva_c']);
foreach ($parametros_generales_array as $params) {
    $outputSELECT[] = $valueSELECT[] = $params;
}
$smarty->assign("option_values_porcentaje_impuesto_principal", $valueSELECT);
$smarty->assign("option_output_porcentaje_impuesto_principal", $outputSELECT);
$smarty->assign("option_selected_porcentaje_impuesto_principal", $parametros_generales[0]['iva']);

$valueSELECT = "";
$outputSELECT = "";
$tipo_array = array("Activo Fijo"=>0, "Consumo"=>1, "Venta"=>2, "Otro"=>3);

foreach ($tipo_array as $key => $params) {
    $outputSELECT[] = $key;
    $valueSELECT[] = $params;
}
$smarty->assign("option_values_tipo", $valueSELECT);
$smarty->assign("option_output_tipo", $outputSELECT);
$smarty->assign("option_selected_tipo", $parametros_generales[0]['iva']);



$valueSELECT = "";
$outputSELECT = "";
$proveedores = $productos->ObtenerFilasBySqlSelect("SELECT * FROM proveedores order by descripcion");
foreach ($proveedores as $prov) {
    $valueSELECT[] = $prov["id_proveedor"];
    $outputSELECT[] = $prov["descripcion"];
}
$smarty->assign("option_values_prov", $valueSELECT);
$smarty->assign("option_output_prov", $outputSELECT);
$smarty->assign("option_selected_prov", $campos_item[0]["proveedor"]);


// Cargando tipo de precio en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM tipo_precio_item");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["id"];
}
$smarty->assign("option_values_tipoP", $arraySelectOption);
$smarty->assign("option_output_tipoP", $arraySelectoutPut);


//cargo la cabecera de la tabla
$campos_precio = $productos->ObtenerFilasBySqlSelect("SELECT * FROM region");


$smarty->assign("campos_precio",$campos_precio);
?>
