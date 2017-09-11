<?php
include("../../libs/php/clases/clientes.php");
include("../../libs/php/clases/cxc.php");
include("../../../menu_sistemas/lib/common.php");

$clientes = new Clientes();
$cxc = new cxc();

/*guardar
*/
if($_POST["cantidad"]!=0){
	//echo $_POST["cantidad_item"];
	for($i=0;$i<=$_POST["cantidad_item"];$i++)
	{
		//echo $_POST["optAgregar".$i];
		
		if ($_POST["optAgregar".$i]!=''){
		
		
		$id_factura_pos=$_POST["id_fac".$i];
		$INSERT="update cxc_edocuenta set marca='A',fecha_registro=CURRENT_TIMESTAMP,fecha_autorizado='".fecha_sql($_POST["fecha"])."' where cod_edocuenta=$id_factura_pos";
		$cxc->ExecuteTrans($INSERT);
		}
	}

}



/**
 * Cabecera del Estado de Cuenta.
 */
 
$datacliente = $clientes->ObtenerFilasBySqlSelect("select * from clientes where id_cliente = ".$_GET["cod"]);
if(count($datacliente)==0){

    $pagina .= "<html>";
    $pagina .= "<body style=\"background-color:#f8f8f8\">";
    $pagina .= "<div  style=\"background-color:#dcdedb; border: 1px solid black;-moz-border-radius: 8px;padding:5px; margin-left: 20%;margin-right: 20%;margin-top:5%;   font-size: 13px; \">";
    $pagina .= "<img src=\"../../libs/imagenes/configuracion.png\"> <b>Disculpe esta operacion esta permitida:</b> <br>
        <span style='color:red;padding-left:30px;'><img src=\"../../libs/imagenes/ico_note.gif\"> Verifique que el cliente al que desea facturar exista.</span><br>";
    $pagina .= "<hr><span style=\"color:#1e6602\">Para mas información contacte al administrador.</span>";
if(count($campos)>0) $pagina .= "<br><span style=\"color:red\"><img style=\"border:none;\" src=\"../../libs/imagenes/ico_list.gif\"> Detalle del error:</span><br><b style=\"padding-left:30px;\"><img src=\"../../libs/imagenes/ico_search.gif\"> Modulo:</b> ".$campos[0]["descripcion_optmenu"]." - <b>Sección:</b> ".$campos[0]["descripcion_optseccion"]." >> <b>".$campos[0]["opt_subseccion"].":</b> ".$campos[0]["descripcion"];
    $pagina .= "<hr><br><br><a style=\"text-decoration:none;\" href='?opt_menu=".$_GET["opt_menu"]."&opt_seccion=".$_GET["opt_seccion"]."'><img style=\"border:none;\" src=\"../../libs/imagenes/ico_back.gif\"> Volver</a>";
    $pagina .= "</div>";
    $pagina .= "</body>";
    $pagina .= "</html>";
    echo utf8_decode($pagina);
    exit;
}
$facs = $cxc->ObtenerFilasBySqlSelect("select * from cxc_edocuenta where id_cliente=".$_GET["cod"]." and marca='E'");
$total = $cxc->ObtenerFilasBySqlSelect("select sum(monto) as monto from cxc_edocuenta where id_cliente=".$_GET["cod"]." and marca='E'");
$smarty->assign("fac",$facs);
$smarty->assign("datacliente",$datacliente);
$smarty->assign("total",$total);





?>
