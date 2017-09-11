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
	for ($i=0; $i <=$_POST['numero']+1; $i++) {
		
		$cb = "codigo_barras".$i;
		$p = "precio".$i;
		$estado = "estado".$i;
		$motivo = "motivo".$i;
		$observacion = "observacion".$i;

		if ($_POST[$cb]!='') {

			if ($_POST[$motivo]=='' || $_POST[$observacion]=='') {

				echo "<script type='text/javascript'>
				alert ('Debe llenar todos los campos');
				history.back(-1);

				</script>";
				exit();
			}
		}
	}
	
	$kk = 0;
	$jj=$_POST['numero']+$_POST['filas'];
	$productos->BeginTrans();

	for($i=0;$i<=$jj;$i++)
	{
		$cb = "codigo_barras".$i;
		$p = "precio".$i;
		$estado = "estado".$i;
		$motivo = "motivo".$i;
		$observacion = "observacion".$i;

		if($_POST[$cb]!='')
		{
			if($kk == 0)
			{
				$idCab = $_POST[id_var_precio_cab];
				$correl = $productos->ObtenerFilasBySqlSelect("SELECT (max(correlativo)+1) as correlativo FROM item_variacion_precio_cabecera");
				$correlativo = $correl[0][correlativo];
				$instruccion = " DELETE FROM item_variacion_precio WHERE id_var_precio_cab = '$idCab'";
				$productos->ExecuteTrans($instruccion);
				$kk = 1;
			}

			$instruccion = " INSERT INTO item_variacion_precio 
			(id_var_precio, codigo_barra, precio_sin_iva, id_var_precio_cab, id_estado, id_motivo, observacion) 
			VALUES ('', '".$_POST[$cb]."','".$_POST[$p]."' ,'".$idCab."', '".$_POST[$estado]."', '".$_POST[$motivo]."', '".$_POST[$observacion]."');";

			//echo $instruccion."<br>";
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

// Cargando estados en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM ".DB_SELECTRA_PYMEPP.".estados");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["nombre_estado"];
    $arraySelectoutPut[] = $item["codigo_estado"];
}
$smarty->assign("option_output_estados", $arraySelectOption);
$smarty->assign("option_values_estados", $arraySelectoutPut);

// Cargando el motivo en combo select
$arraySelectOption = "";
$arraySelectoutPut = "";
$campos_comunes = $productos->ObtenerFilasBySqlSelect("SELECT * FROM motivo_varia_precio");
foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["nombre_motivo"];
    $arraySelectoutPut[] = $item["id_motivo"];
}
$smarty->assign("option_output_motivo", $arraySelectOption);
$smarty->assign("option_values_motivo", $arraySelectoutPut);

// Cargando los codigos de barra, precios y estados
$arraySelectOption = "";
$arraySelectoutPut = "";
$registros = $productos->ObtenerFilasBySqlSelect("SELECT ivp.*, i.descripcion1 FROM item_variacion_precio ivp
INNER JOIN item i ON i.codigo_barras = ivp.codigo_barra WHERE id_var_precio_cab='".$_GET[cod]."' ");
$filas=count($registros);
/*foreach ($campos_comunes as $key => $item) {
    $arraySelectOption[] = $item["codigo_siga"];
    $arraySelectoutPut[] = $item["descripcion_siga"];
}
$smarty->assign("option_output_siga", $arraySelectoutPut);
$smarty->assign("option_values_siga", $arraySelectOption);*/
$smarty->assign("registros", $registros);
if(isset($_GET['boton_agregar']) && !isset($_POST["aceptar"]))
{
	$smarty->assign("numero_veces",$_GET['boton_agregar']+1);

}
else
{
	$smarty->assign("numero_veces", 1);
	
}

$smarty->assign("cod", $_GET[cod]);
$smarty->assign("filas", $filas);
$smarty->assign("cant", '20');
?>