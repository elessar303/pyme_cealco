<?php
error_reporting(E_ALL^E_NOTICE);
ini_set("display_errors", 1);
include("../../libs/php/clases/producto.php");
include("../../../menu_sistemas/lib/common.php");
$productos = new Producto();
$comun = new Comunes();

$bdCentral= "selectrapyme_central";
$bdPyme = "selectrapyme";

if(isset($_POST["aceptar"]))
{
	for ($i=0; $i <=$_POST['num_campos']; $i++) {
		
		$cb = "codigo_barras".$i;
		$p = "precio".$i;
		$estado = "estado".$i;
		$motivo = "motivo".$i;
		$observacion = "observacion".$i;

		if ($_POST[$cb]!='') {
			
			if ($_POST[$motivo]=='' && $_POST[$observacion]=='') {
				echo "<script type='text/javascript'>
				alert ('Debe llenar todos los campos');
				history.back(-1);

				</script>";
				exit();
			}

			if ($_POST[$motivo]=='') {
				echo "<script type='text/javascript'>
				alert ('Debe colocar el motivo de la variacion');
				history.back(-1);

				</script>";
				exit();
			}

			if ($_POST[$observacion]=='') {
				echo "<script type='text/javascript'>
				alert ('Debe llenar la observacion');
				history.back(-1);

				</script>";
				exit();
			}
		}
	}
	
	$kk = 0;
	$productos->BeginTrans();
	for($i=0;$i<=$_POST['num_campos'];$i++)
	{
		$cb = "codigo_barras".$i;
		$p = "precio".$i;
		$estado = "estado".$i;
		$motivo = "motivo".$i;
		$observacion = "observacion".$i;

		if($_POST[$cb]!='')
		{
			/*Lo comento porque ya ataja el usuario, pero quiero probar otra forma de tomarlo
			hz
			$login = new Login();
			$smarty->assign("nombre_usuario", $login->getNombreApellidoUSuario());*/

			if($kk == 0)
			{
				$correl = $productos->ObtenerFilasBySqlSelect("SELECT (max(correlativo)+1) as correlativo FROM item_variacion_precio_cabecera");
				$correlativo = $correl[0][correlativo];
				$instruccion = " INSERT INTO item_variacion_precio_cabecera
				(id_var_precio_cab, fecha, usuario, correlativo) 
				VALUES ('', '".date("Y-m-d")."','".$login->getNombreApellidoUSuario()."', '$correlativo');";
				$productos->ExecuteTrans($instruccion);
				$kk = 1;
				$idCab = $productos->getInsertID();
			}

			$instruccion = " INSERT INTO item_variacion_precio 
			(id_var_precio, codigo_barra, precio_sin_iva, id_var_precio_cab, id_estado, id_motivo, observacion) 
			VALUES ('', '".$_POST[$cb]."','".$_POST[$p]."' ,'".$idCab."', '".$_POST[$estado]."', '".$_POST[$motivo]."', '".$_POST[$observacion]."');";
			
			$productos->ExecuteTrans($instruccion);

			$ivas = $productos->ObtenerFilasBySqlSelect("SELECT iva FROM item WHERE codigo_barras = '".$_POST[$cb]."' ");
			$iva = $ivas[0][iva];
			$precio_iva = $_POST[$p]+(($_POST[$p] * $iva)/100);
			$instruccion = " UPDATE item SET precio1='".$_POST[$p]."', precio2='".$_POST[$p]."', precio3='".$_POST[$p]."', coniva1='$precio_iva' , coniva2='$precio_iva', coniva3='$precio_iva', fecha_ingreso='".date('Y-m-d')."' WHERE codigo_barras = '".$_POST[$cb]."' ;";
			$productos->ExecuteTrans($instruccion);			
		}
		
	}

	if ($productos->errorTransaccion == 1) {
        Msg::setMessage("<span style=\"color:#62875f;\">Producto Generado Exitosamente con en Nro. " . $nro_producto . "</span>");
    }
    if ($productos->errorTransaccion == 0) {
        Msg::setMessage("<span style=\"color:red;\">Error al tratar de crear el producto.</span>");
    }
	$productos->CommitTrans($productos->errorTransaccion);
	//exit;
	header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
}

// Cargando rubro en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM $bdCentral.estados");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["nombre_estado"];
    $arraySelectoutPut[] = $item["codigo_estado"];
}
$smarty->assign("option_output_estados", $arraySelectOption);
$smarty->assign("option_values_estados", $arraySelectoutPut);

// Cargando el motivo en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM $bdPyme.motivo_varia_precio");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["nombre_motivo"];
    $arraySelectoutPut[] = $item["id_motivo"];
}
$smarty->assign("option_output_motivo", $arraySelectOption);
$smarty->assign("option_values_motivo", $arraySelectoutPut);

// Cargando la descripcion segun el tipo de NO ALIMENTO
/*$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $almacen->ObtenerFilasBySqlSelect("SELECT * FROM $pyme.codigos_siga");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["codigo_siga"];
    $arraySelectoutPut[] = $item["descripcion_siga"];
}
$smarty->assign("option_output_siga", $arraySelectoutPut);
$smarty->assign("option_values_siga", $arraySelectOption);*/

$smarty->assign("cant", '50');
?>