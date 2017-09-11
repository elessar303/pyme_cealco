<?php

include("../../libs/php/clases/proveedores.php");
include("../../libs/php/clases/correlativos.php");
include("../../../menu_sistemas/lib/common.php") ;
$proveedores = new Proveedores();
$correlativos = new Correlativos();


if(isset($_POST["aceptar"])){


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

$proveedores->BeginTrans();
$instruccion =  "UPDATE  proveedores SET 
	    compania =  '".$_POST["compania"]."',
            descripcion =  '".$_POST["descripcion"]."',
            direccion = '".$_POST["direccion"]."',
            telefonos='".$_POST["telefonos"]." ',
            fax='".$_POST["fax"]."',
            email= '".$_POST["email"]."',
            rif='".$_POST["rif"]."',
            nit='".$_POST["nit"]." ',
	    estatus='".$_POST["estatus"]."',
	    cod_impuesto_proveedor='".$_POST["cod_impuesto_proveedor"]."',
                cod_entidad='".$_POST["cod_entidad"]."',
		cod_especialidad='".$_POST["cod_especialidad"]."',
		clase_proveedor='".$_POST["id_pclasif"]."',	
                cuenta_contable='".$_POST["cuenta_contable"]."', mostrar='".$_POST["mostrar"]."'
                 WHERE id_proveedor = '".$_POST["id_proveedor"]."'
";

$proveedores->ExecuteTrans($instruccion);

if($foto!="")
{
	$instruccion = "UPDATE proveedores SET foto = '".$foto."' WHERE id_proveedor = '".$_POST["id_proveedor"]."'";
   $proveedores->ExecuteTrans($instruccion);
}

//if($proveedores->errorTransaccion==1){Msg::setMessage("<span style=\"color:#62875f;\">Modificacion realizada existosamente</span>");}
if($proveedores->errorTransaccion==0){Msg::setMessage("<span style=\"color:red;\">Error al tratar de realizar la transaccion.</span>");}
$proveedores->CommitTrans($proveedores->errorTransaccion);
header("Location: ?opt_menu=".$_POST["opt_menu"]."&opt_seccion=".$_POST["opt_seccion"]);
exit;
}



if(isset($_GET["cod"])){
    


$campos = $proveedores->ObtenerFilasBySqlSelect("select * from proveedores where id_proveedor = ".$_GET["cod"]);
$smarty->assign("datos_proveedores",$campos);
//$smarty->assign("campos_proveedores",$campos_proveedores);

}

//Clasificicacion
$valueSELECT = "";
$outputSELECT =  "";
$tprecio  = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_proveedor_clasif");
foreach($tprecio as $key => $item){
    $valueSELECT[] = $item["id_pclasif"];
    $outputSELECT[] = $item["clasificacion"];
}
$smarty->assign("option_values_clasi",$valueSELECT);
$smarty->assign("option_output_clasi",$outputSELECT);
$smarty->assign("option_selected_clasi",$campos[0]["clase_proveedor"]);

//CONSULTA DE ID FISCAL EN PARAMETROS
$valueSELECT = "";
$outputSELECT =  "";
$data_parametros  = $proveedores->ObtenerFilasBySqlSelect("select * from parametros_generales");
foreach($data_parametros as $key => $item){
    $valueSELECT[] = $item["cod_empresa"];
    $outputidfiscalSELECT[] = $item["id_fiscal"];
    $outputidfiscal2SELECT[] = $item["id_fiscal2"];
}
$smarty->assign("option_values_parametros",$valueSELECT);
$smarty->assign("option_output_idfiscal",$outputidfiscalSELECT);
$smarty->assign("option_output_idfiscal2",$outputidfiscal2SELECT);

//

//$campos_proveedores = $productos->ObtenerFilasBySqlSelect("select * from proveedores where id_proveedor = ".$_GET["id_proveedor"]);

//TIPO DE ENTIDAD
$valueSELECT = "";
$outputSELECT =  "";
$tprecio  = $proveedores->ObtenerFilasBySqlSelect("select * from entidades");
foreach($tprecio as $key => $item){
    $valueSELECT[] = $item["cod_entidad"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_entidad",$valueSELECT);
$smarty->assign("option_output_entidad",$outputSELECT);
$smarty->assign("option_selected_entidad",$campos[0]["cod_entidad"]);


//ESPECIALIDAD
$valueSELECT = "";
$outputSELECT =  "";
$tprecio  = $proveedores->ObtenerFilasBySqlSelect("select * from especialidades_proveedor order by especialidad ASC");
foreach($tprecio as $key => $item){
    $valueSELECT[] = $item["cod_especialidad"];
    $outputSELECT[] = $item["especialidad"];
}
$smarty->assign("option_values_especialidad",$valueSELECT);
$smarty->assign("option_output_especialidad",$outputSELECT);
$smarty->assign("option_selected_especialidad",$campos[0]["cod_especialidad"]);

// RETENCION DEL I.V.A.
$valueSELECT = "";
$outputSELECT =  "";
$tprecio  = $proveedores->ObtenerFilasBySqlSelect("select * from lista_impuestos where cod_tipo_impuesto = 1");
foreach($tprecio as $key => $item){
    $valueSELECT[] = $item["cod_impuesto"];
    $outputSELECT[] = $item["descripcion"];
}
$smarty->assign("option_values_impuesto",$valueSELECT);
$smarty->assign("option_output_impuesto",$outputSELECT);
$smarty->assign("option_selected_impuesto",$campos[0]["cod_impuesto_proveedor"]);


//Cargar % I.V.A de la tabla de parametros generales.
$parametros_generales = $proveedores->ObtenerFilasBySqlSelect("select * from parametros_generales");
$smarty->assign("parametros_generales",$parametros_generales );

//Cargar Almacenes
$almacenes= $proveedores->ObtenerFilasBySqlSelect("select * from almacen");
$smarty->assign("almacenes",$almacenes );

// Cargando tipo_proveedor en combo select
 $arraySelectOption = $arraySelectoutPut = "";
$campos_comunes = $proveedores->ObtenerFilasBySqlSelect("select * from tipo_proveedor");
foreach($campos_comunes as $key => $item){
    $arraySelectOption[] = $item["cod_tipo_proveedor"];
    $arraySelectoutPut[] = $item["descripcion"];
}
$smarty->assign("option_values_tipo_proveedor",$arraySelectOption);
$smarty->assign("option_output_tipo_proveedor",$arraySelectoutPut);

// CONSULTA DE CUENTAS CONTABLES
$global=new bd(SELECTRA_CONF_PYME);
$sentencia="select * from nomempresa where bd='".$_SESSION['EmpresaFacturacion']."'";
$contabilidad = $global->query($sentencia);
$fila = $contabilidad->fetch_assoc();

$valueSELECT = "";
$outputSELECT =  "";
$contabilidad = $proveedores->ObtenerFilasBySqlSelect("select * from ".$fila['bd_contabilidad'].".cwconcue where Tipo='P'");
foreach($contabilidad as $key => $cuenta){
    $valueSELECT[] = $cuenta["Cuenta"];
    $outputSELECT[] = $cuenta["Cuenta"]." - ".$cuenta["Descrip"];
}
$smarty->assign("option_values_cuenta",$valueSELECT);
$smarty->assign("option_output_cuenta",$outputSELECT);
$smarty->assign("option_selected_cuenta",$campos[0]["cuenta_contable"]);


?>
