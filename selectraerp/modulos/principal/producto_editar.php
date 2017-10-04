<?php

include("../../libs/php/clases/producto.php");
include("../../../menu_sistemas/lib/common.php");

$productos = new Producto();

$campos_almacen = $productos->ObtenerFilasBySqlSelect("SELECT * FROM almacen");
$smarty->assign("campos_almacen", $campos_almacen);

// $almacenes2 = $productos->ObtenerFilasBySqlSelect("SELECT descripcion, cantidad, id_item FROM almacen al RIGHT JOIN item_existencia_almacen it ON (it.cod_almacen = al.cod_almacen) WHERE it.id_item = '{$_GET["cod"]}';");
$almacenes2 = $productos->ObtenerFilasBySqlSelect("select a.descripcion,i.cantidad , u.descripcion as ubicacion from almacen  as a,item_existencia_almacen as i, ubicacion as u where a.cod_almacen=i.cod_almacen and u.id=i.id_ubicacion and i.id_item='".$_GET["cod"]."';");
$datos_item = $productos->ObtenerFilasBySqlSelect("SELECT iva, tipo_prod FROM item WHERE id_item = '{$_GET["cod"]}';");
$smarty->assign("valor_iva", $datos_item[0]["iva"]);

if (isset($_POST["aceptar"])) 
{
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
	
	if(isset($_POST['sae']))
    {
    	if(!isset($_POST['impresion']) || $_POST['impresion']=='NULL')
    	{
    		echo "
	    			<script language='JavaScript'>
	    				alert('Error, Seleccione Tipo de Impresion de Producto');
	    				history.go(-1);
	    			</script>
    			"; exit();

    	}
    }
    $_POST["iva"] = $_POST["monto_exento"] == 0 ? $_POST["iva"] : 0;
    $instruccion = "UPDATE item SET cod_item = '" . $_POST["cod_item"] . "', codigo_barras = '" . $_POST["cod_barras"] . "',
    		codigo_cpe = '".$_POST["cod_cpe"]."', costo_actual = '" . $_POST["costo_actual"] . "', descripcion1 = '" . $_POST["descripcion1"] . "',
            descripcion2 = '" . $_POST["descripcion2"] . "', descripcion3 = '" . $_POST["descripcion3"] . "',
            referencia = '" . $_POST["referencia"] . "', codigo_fabricante = '" . $_POST["codigo_fabricante"] . "',
            monto_exento = '" . $_POST["monto_exento"] . "',
            iva = '" . $_POST["iva"] . "', cod_departamento = '" . $_POST["cod_departamento"] . "',
            cod_grupo = '" . $_POST["cod_grupo"] . "', sub_categoria = " . $_POST["sub_categoria"] . ", id_marca =" . $_POST["marca"] . ", cod_linea = '" . $_POST["cod_linea"] . "',
            estatus = '" . $_POST["estatus"] . "', existencia_min = '" . $_POST["existencia_min"] . "',
            existencia_max = '" . $_POST["existencia_max"] . "', unidad_empaque = '" . $_POST["empaque"] . "',
            cantidad = '" . $_POST["unidad_empaque"] . "', seriales = '" . $_POST["serial"] . "',
            garantia = '" . $_POST["garantia"] . "', tipo_item = '" . $_POST["tipo_producto"] . "', tipo_prod = " . $_POST["tipo"] . ",
            costo_promedio = '" . $_POST["costo_promedio"] . "', costo_anterior = '" . $_POST["costo_anterior"] . "',
            cuenta_contable1 = '" . $_POST["cuenta_contable1"] . "', cuenta_contable2 = '" . $_POST["cuenta_contable2"] . "', serial1= '".$_POST["serial1"]. "',
            cantidad_bulto='".$_POST["cantidad_bulto"]."', kilos_bulto='".$_POST["kilos_bulto"]."', proveedor='".$_POST["proveedor"]."', 
            fecha_ingreso='".$_POST["fecha_ingreso"]."', origen='".$_POST["origen"]."', costo_cif='".$_POST["costo_cif"]."',
            costo_origen='".$_POST["costo_origen"]."', temporada='".$_POST["temporada"]."', mate_compo_clase='".$_POST["mate_compo_clase"]."',
            punto_pedido='".$_POST["punto_pedido"]."', tejido='".$_POST["tejido"]."', reg_sanit='".$_POST["reg_sanit"]."', 
            cod_barra_bulto='".$_POST["cod_barra_bulto"]."', observacion='".$_POST["observacion"]."', cont_licen_nro='".$_POST["cont_licen_nro"]."',
            precio_cont='".$_POST["precio_cont"]."', aprob_arte='".$_POST["aprob_arte"]."', propiedad='".$_POST["propiedad"]."', regulado='".$_POST["regulado"]."',
            cestack_basica='".$_POST["cestack_basica"]."', bcv='".$_POST["bcv"]."', clap='".$_POST["clap"]."', unidadxpeso='".$_POST["unidadxpeso"]."', pesoxunidad='".$_POST["pesoxunidad"]."', unidad_venta='".$_POST["unidad_venta"]."',
            producto_vencimiento='".$_POST["producto_vencimiento"]."', tipo_almacenamiento='".$_POST["tipo_almacenamiento"]."',
            central='".$_POST["central"]."', nacional='".$_POST["nacional"]."', precio_bulto=".$_POST["precio_bulto"].", sae='".$_POST["sae"]."', impresion='".$_POST["impresion"]."', id_arancel='".$_POST["codigo_arancelario"]."', unidad_paleta='".$_POST["unidad_paleta"]."'
        WHERE id_item = '" . $_GET["cod"] . "'";
    // Originalmente: WHERE cod_item = '" . $_POST["cod_item"] . "'";
    // Modificado para permitir edita el codigo del item.
    //echo $instruccion;exit;
    $productos->Execute2($instruccion);
	if($foto!="")
	{
		$instruccion = "UPDATE item SET foto = '".$foto."' WHERE id_item = '" . $_GET["cod"] . "'";
	   $productos->Execute2($instruccion);
	}
	if($foto1!="")
	{
		$instruccion = "UPDATE item SET foto1 = '".$foto1."' WHERE id_item = '" . $_GET["cod"] . "'";
	   $productos->Execute2($instruccion);
	}
	if($foto2!="")
	{
		$instruccion = "UPDATE item SET foto2 = '".$foto2."' WHERE id_item = '" . $_GET["cod"] . "'";
	   $productos->Execute2($instruccion);
	}
	if($foto3!="")
	{
		$instruccion = "UPDATE item SET foto3 = '".$foto3."' WHERE id_item = '" . $_GET["cod"] . "'";
	   $productos->Execute2($instruccion);
	}
	if($foto4!="")
	{
		$instruccion = "UPDATE item SET foto4 = '".$foto4."' WHERE id_item = '" . $_GET["cod"] . "'";
	   $productos->Execute2($instruccion);
	}
    $i = 1;
    $codigo = $_POST['id' . $i];

    $query = "DELETE FROM productos_kit
    WHERE id_item_padre = (SELECT id_item FROM item WHERE cod_item = '{$_POST['cod_item']}');";

    $productos->ExecuteTrans($query);

    while ($codigo != '') {

        if ($_POST['id' . $i] != '') {
            $query = "insert into productos_kit  values ((select id_item from item where cod_item= '" . $_POST['cod_item'] . "'),'" . $_POST['item' . $i] . "','" . $_POST['cantidad' . $i] . "' );";
            $productos->ExecuteTrans($query);
        }
        $codigo = $_POST['id' . $i];
        $i++;
    }

    header("Location: ?opt_menu=" . $_POST["opt_menu"] . "&opt_seccion=" . $_POST["opt_seccion"]);
}

$campos_item = $productos->ObtenerFilasBySqlSelect("SELECT *, round(precio1 / (1 + (utilidad1/100)),2) AS p1, round(precio2 / (1 + (utilidad2/100)),2) AS p2 , round(precio3 / (1 + (utilidad3/100)),2) AS p3 FROM item WHERE id_item = {$_GET["cod"]};");
$smarty->assign("campos_item", $campos_item);

/*Evaluo si el rubro esta cotizado*/
$campos_cotizacion = $productos->ObtenerFilasBySqlSelect("SELECT cmd._pvp,cmd._estatus_producto,cme.estatus_name
                    FROM selectrapyme_central.cotizacion_mercado_detalle cmd
                    INNER JOIN selectrapyme.item i ON i.id_item = cmd.id_producto
                    INNER JOIN selectrapyme_central.cotizacion_mercado_estatus cme ON cme.id_estatus = cmd._estatus_producto
                    WHERE i.id_item = '{$_GET["cod"]}'
                    ORDER BY cmd._pvp DESC
                    LIMIT 1");

if (count($campos_cotizacion)!=0) {
	$varCotiza = 1;
	$camposcotiza = $campos_cotizacion[0]["_pvp"];

	$smarty->assign("varCotiza",$varCotiza);
	$smarty->assign("camposcotiza",$camposcotiza);

	// echo "[{'rc':'-1','mensaje1':'".$campos2[0]["_pvp"]."','mensaje2':'".$campos2[0]["estatus_name"]."'}]";
}else{
	$varCotiza = 0;
	$camposcotiza = 0;

	$smarty->assign("varCotiza",$varCotiza);
	$smarty->assign("camposcotiza",$camposcotiza);
	// echo "[{'rc':'-1','mensaje1':'0','mensaje2':'Rubro no cotizado'}]";
}
/*Fin de la evaluación*/

$campos_kit = $productos->ObtenerFilasBySqlSelect("SELECT * FROM productos_kit p, item i WHERE p.id_item_hijo = i.id_item AND p.id_item_padre = '{$_GET['cod']}';");

// Cargando departamentos en combo select
$campos_comunes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM departamentos");
$arraySelectOption = "";
$arraySelectoutPut = "";
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_departamento"];
}
$smarty->assign("option_values_departamentos", $arraySelectOption);
$smarty->assign("option_output_departamentos", $arraySelectoutPut);
$smarty->assign("option_selected_departamentos", $campos_item[0]["cod_departamento"]);

// // Cargando grupo en combo select

 $campo = $productos->ObtenerFilasBySqlSelect("select * from item where id_item =".$_GET["cod"]);
 $smarty->assign("option_selected_subrubro", $campo[0]["cod_grupo"]);
 $smarty->assign("option_selected_marca", $campo[0]["id_marca"]);
 $smarty->assign("option_selected_unidadxpeso", $campo[0]["unidadxpeso"]);
 $smarty->assign("option_selected_unidad_venta", $campo[0]["unidad_venta"]);
/*Seleccion de sub grupo*/
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM sub_grupo WHERE cod_grupo = ".$campo[0]["cod_grupo"]);
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["id_sub_grupo"];
}
$smarty->assign("option_values_grupo", $arraySelectOption);
$smarty->assign("option_output_grupo", $arraySelectoutPut);
 $smarty->assign("option_selected_sub_categoria", $campo[0]["sub_categoria"]);
//$smarty->assign("option_selected_sub_categoria", $campo[0]["sub_categoria"]);

/*Selección de código arancelario*/
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes= $productos->ObtenerFilasBySqlSelect("SELECT id, CONCAT(codigo_Arancelario,' ',descripcion_rubro,' ',descripcion_arancel) as descripcion
	FROM codigos_arancelarios");
// $campos_comunes = $campos_comunes->set_charset("utf8");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id"];
    $arraySelectoutPut[] = utf8_encode($item["descripcion"]);
    // $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_arancel", $arraySelectOption);
$smarty->assign("option_output_arancel", $arraySelectoutPut);
$smarty->assign("option_selected_arancel", $campos_item[0]["id_arancel"]);
/*fin de la selección del código arancelario*/

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
    $arraySelectOption[] = $item["descripcion"];
    $arraySelectoutPut[] = $item["cod_linea"];
}
$smarty->assign("option_values_linea", $arraySelectOption);
$smarty->assign("option_output_linea", $arraySelectoutPut);
$smarty->assign("option_selected_linea", $campos_item[0]["cod_linea"]);

//Cargar % I.V.A de la tabla de parametros generales.
$parametros_generales = $productos->ObtenerFilasBySqlSelect("SELECT * FROM parametros_generales");
$smarty->assign("parametros_generales", $parametros_generales);

$camposKit = ($campos_kit[0]['id_item'] != '') ? 'checked' : '';

$campoSerial = ($campos_item[0]['seriales'] == '1') ? 'checked' : '';

$campoGarantia = ($campos_item[0]['garantia'] == '1') ? 'checked' : '';

if ($campos_item[0]['tipo_item'] == '1')
    $campoImportado = 'checked';
else
    $campoNacional = 'checked';

//Cargar Almacenes
$almacenes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM almacen");
$smarty->assign("almacenes", $almacenes);

$smarty->assign("campoSerial", $campoSerial);
$smarty->assign("campoGarantia", $campoGarantia);
$smarty->assign("campoImportado", $campoImportado);
$smarty->assign("campoNacional", $campoNacional);
$smarty->assign("campos_kit", $camposKit);
$smarty->assign("almacenes2", $almacenes2);

// CONSULTA DE CUENTAS CONTABLES
$global = new bd(SELECTRA_CONF_PYME);
$sentencia = "SELECT * FROM nomempresa WHERE bd='{$_SESSION['EmpresaFacturacion']}';";
$contabilidad = $global->query($sentencia);
$fila = $contabilidad->fetch_assoc();

$valueSELECT = "";
$outputSELECT = "";
$contabilidad = $productos->ObtenerFilasBySqlSelect("SELECT * FROM {$fila['bd_contabilidad']}.cwconcue WHERE Tipo='P';");
$fila=$productos->getFilas();
if($fila!=0){
	foreach ($contabilidad as $cuenta) {
	    $valueSELECT[] = $cuenta["Cuenta"];
	    $outputSELECT[] = $cuenta["Cuenta"] . " - " . $cuenta["Descrip"];
	}
	$smarty->assign("option_values_cuenta", $valueSELECT);
	$smarty->assign("option_output_cuenta", $outputSELECT);
	$smarty->assign("option_selected_cuenta1", $campos_item[0]["cuenta_contable1"]);
	$smarty->assign("option_selected_cuenta2", $campos_item[0]["cuenta_contable2"]);
}
$valueSELECT = "";
$outputSELECT = "";
#$parametros_generales = $productos->ObtenerFilasBySqlSelect("SELECT porcentaje_impuesto_principal, iva_a, iva_b, iva_c, (SELECT iva FROM item WHERE id_item = '{$_GET["cod"]}') AS iva FROM parametros_generales;");
$parametros_generales = $productos->ObtenerFilasBySqlSelect("SELECT porcentaje_impuesto_principal, iva_a, iva_b, iva_c FROM parametros_generales;");
$parametros_generales_array = array($parametros_generales[0]['porcentaje_impuesto_principal'], $parametros_generales[0]['iva_a'], $parametros_generales[0]['iva_b'], $parametros_generales[0]['iva_c']);
foreach ($parametros_generales_array as $params) {
    $outputSELECT[] = $valueSELECT[] = $params;
}
$smarty->assign("option_values_porcentaje_impuesto_principal", $valueSELECT);
$smarty->assign("option_output_porcentaje_impuesto_principal", $outputSELECT);
$smarty->assign("option_selected_porcentaje_impuesto_principal", $datos_item[0]['iva']);
#$smarty->assign("option_selected_porcentaje_impuesto_principal", $datos_item[0]["iva"]);

$valueSELECT = "";
$outputSELECT = "";
$tipo_array = array("Activo Fijo"=>0, "Consumo"=>1, "Venta"=>2, "Otro"=>3);

foreach ($tipo_array as $key => $params) {
    $outputSELECT[] = $key;
    $valueSELECT[] = $params;
}
$smarty->assign("option_values_tipo", $valueSELECT);
$smarty->assign("option_output_tipo", $outputSELECT);
$smarty->assign("option_selected_tipo", $campos_item[0]["tipo_prod"]);

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

//impresion_entrega
// Cargando tipo de precio en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM impresion_entrega");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["id"];
    $arraySelectoutPut[] = $item["valor"];
}
$smarty->assign("option_values_impresion", $arraySelectOption);
$smarty->assign("option_output_impresion", $arraySelectoutPut);
$smarty->assign("option_selected_impresion", $campos_item[0]["impresion"]);
//fin

?>
